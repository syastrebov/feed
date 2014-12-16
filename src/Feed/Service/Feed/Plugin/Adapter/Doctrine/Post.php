<?php

namespace Feed\Service\Feed\Plugin\Adapter\Doctrine;

use Doctrine\ORM\Query;
use Feed\Service\Feed\GetList\Entity\Collection as FeedCollection;
use Feed\Service\Feed\Plugin\Adapter\PostInterface;

/**
 * Лента постов / Получение содержимого постов
 *
 * Class Post
 * @package Feed\Service\FeedPlugin\Plugin\Adapter\Doctrine
 */
final class Post extends AbstractAdapter implements PostInterface
{
    /**
     * Получить данные по постам по id
     *
     * @param array $ids
     * @return FeedCollection
     */
    public function getPostsByIds(array $ids)
    {
        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder
            ->select('f')
            ->from('Feed\Entity\Feed', 'f')
            ->where($queryBuilder->expr()->in('f.id', $ids));

        $result = $queryBuilder
            ->getQuery()
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true)
            ->setHint(Query::HINT_REFRESH, true)
            ->getResult();

        $collection = new FeedCollection();
        foreach ($result as $entity) {
            /** @var \Feed\Entity\Feed $entity */
            $collection->attach($entity);
        }

        return $collection;
    }
}
