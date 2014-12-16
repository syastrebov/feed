<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Entity\Feed as FeedEntity;
use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Feed\Service\Feed\Plugin\Adapter\UserInterface as Adapter;
use Member\Entity\Member;

/**
 * Лента постов / Плагин подключения модели пользователя к посту
 *
 * Class User
 * @package Feed\Service\FeedPlugin\Plugin
 */
final class User extends AbstractPlugin
{
    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * Constructor
     *
     *
     * @param Adapter $adapter
     */
    public function __construct(Adapter $adapter)
    {
        $this->adapter  = $adapter;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedContentInterface::TYPE_USER;
    }

    /**
     * Применить плагин к коллекции
     *
     * @param EntityCollection $collection
     * @return EntityCollection
     */
    public function apply(EntityCollection $collection)
    {
        foreach ($collection as $entity) {
            $this->applyEntity($entity);
        }

        return $collection;
    }

    /**
     * Применить плагин к отдельному объекту
     *
     * @param FeedEntity $entity
     * @return mixed
     */
    public function applyEntity(FeedEntity $entity)
    {
        $members = $this->adapter->getMembersByIds([$entity->getMemberId()]);
        if ($members->count() > 0) {
            /** @var Member $member */
            $member = $members->pop();
            $entity->setMember($member);
        }

        return $entity;
    }
}
