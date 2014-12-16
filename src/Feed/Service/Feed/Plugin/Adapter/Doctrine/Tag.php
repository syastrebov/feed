<?php

namespace Feed\Service\Feed\Plugin\Adapter\Doctrine;

use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\ORM\Query;
use Feed\Service\Feed\Plugin\Entity\FeedTag;
use Feed\Service\Feed\Plugin\Entity\FeedTagCollection;
use Feed\Service\Feed\Plugin\Adapter\TagInterface;

/**
 * Лента постов / Получение тегов
 *
 * Class Tag
 * @package Feed\Service\FeedPlugin\Plugin\Adapter\Doctrine
 */
final class Tag extends AbstractAdapter implements TagInterface
{
    /**
     * Получить теги по id постов
     *
     * @param array $ids
     * @return FeedTagCollection
     */
    public function getTagsByFeedIds(array $ids)
    {
        $rsm   = new ResultSetMapping();
        $query = $this->entityManager->createNativeQuery(
            'SELECT id, name, type_id, feed_id FROM tag t ' .
            'JOIN feed_tag ft ON t.id = ft.tag_id WHERE ft.feed_id IN (:feedIds)',
            $rsm
        );

        $query
            ->setParameter('feedIds', $ids)
            ->setHint(Query::HINT_FORCE_PARTIAL_LOAD, true);
        $rsm
            ->addEntityResult('Application\Entity\Tag', 't')
            ->addFieldResult('t', 'id', 'id')
            ->addFieldResult('t', 'name', 'name')
            ->addFieldResult('t', 'type_id', 'typeId')
            ->addScalarResult('feed_id', 'feedId');

        $result = $query->getResult();

        $collection = new FeedTagCollection();
        foreach ($result as $rawEntity) {
            $collection->attach(new FeedTag($rawEntity['feedId'], $rawEntity[0]));
        }

        return $collection;
    }
}
