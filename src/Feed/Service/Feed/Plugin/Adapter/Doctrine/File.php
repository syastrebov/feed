<?php

namespace Feed\Service\Feed\Plugin\Adapter\Doctrine;

use Doctrine\ORM\Query;
use Feed\Service\Feed\Plugin\Adapter\FileInterface;
use Feed\Service\Feed\Plugin\Entity\FeedFileCollection;

/**
 * Лента постов / Получение файлов
 *
 * Class File
 * @package Feed\Service\Feed\Plugin\Adapter\Doctrine
 */
final class File extends AbstractAdapter implements FileInterface
{
    /**
     * Получить файлы по id постов
     *
     * @param array $ids
     * @return FeedFileCollection
     */
    public function getFilesByFeedIds(array $ids)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('f')
            ->from('Feed\Entity\FeedFile', 'f')
            ->where($queryBuilder->expr()->in('f.feedId', $ids));

        $result = $queryBuilder
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->setHint(Query::HINT_REFRESH, true)
            ->getResult();

        $collection = new FeedFileCollection();
        foreach ($result as $entity) {
            /** @var \Feed\Entity\FeedFile $entity */
            $collection->attach($entity);
        }

        return $collection;
    }
}