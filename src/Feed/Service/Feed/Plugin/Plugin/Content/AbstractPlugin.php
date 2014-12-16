<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Feed\Entity\Feed as FeedEntity;
use Feed\Service\Feed\Plugin\Entity\MemberCollection;

/**
 * Лента постов / Базовый плагин наполениня контентом
 *
 * Class AbstractPlugin
 * @package Feed\Service\FeedPlugin\Plugin\Content
 */
abstract class AbstractPlugin implements FeedContentInterface
{
    /**
     * Получить id пользователей в коллекции
     *
     * @param EntityCollection $collection
     * @return array
     */
    protected function getUserIds(EntityCollection $collection)
    {
        $ids = [];
        foreach ($collection as $entity) {
            /** @var FeedEntity $entity */
            if (!in_array($entity->getMemberId(), $ids, true) && (int)$entity->getMemberId() > 0) {
                $ids[] = $entity->getMemberId();
            }
        }

        return $ids;
    }

    /**
     * Получить id постов в коллекции
     *
     * @param EntityCollection $collection
     * @return array
     */
    protected function getFeedIds(EntityCollection $collection)
    {
        $ids = [];
        foreach ($collection as $feed) {
            /** @var FeedEntity $feed */
            $ids[] = $feed->getId();
        }

        return $ids;
    }

    /**
     * Получить пользователей из коллекции постов
     *
     * @param EntityCollection $collection
     * @return MemberCollection
     */
    protected function getMembers(EntityCollection $collection)
    {
        $members = new MemberCollection();
        foreach ($collection as $entity) {
            /** @var FeedEntity $entity */
            if ($entity->getMember() && $entity->getMember()->getId() > 0) {
                if (!$members->getById($entity->getMember()->getId(), false)) {
                    $members->attach($entity->getMember());
                }
            }
        }

        return $members;
    }
}
