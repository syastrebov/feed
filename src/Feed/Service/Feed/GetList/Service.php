<?php

namespace Feed\Service\Feed\GetList;

use Feed\Entity\Feed;
use Feed\Service\Feed\GetList\Adapter\InsertInterface;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Entity\Response;
use Feed\Service\Feed\GetList\Tab\Collection as TabCollection;

use Zend\EventManager\EventManagerInterface as EventManager;

/**
 * Лента постов / Сервис выборки
 *
 * Class Service
 * @package Application\Service\Feed
 */
class Service
{
    /**
     * @var SelectInterface
     */
    private $select;

    /**
     * @var InsertInterface
     */
    private $insert;

    /**
     * @var TabCollection
     */
    private $tabCollection;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     * Constructor
     *
     * @param SelectInterface $select
     * @param InsertInterface $insert
     * @param TabCollection   $tabCollection
     * @param EventManager    $eventManager
     */
    public function __construct(
        SelectInterface $select,
        InsertInterface $insert,
        TabCollection   $tabCollection,
        EventManager    $eventManager
    ) {
        $this->select        = $select;
        $this->insert        = $insert;
        $this->tabCollection = $tabCollection;
        $this->eventManager  = $eventManager;
    }

    /**
     * Добавить элемент в базу
     *
     * @param Feed $entity
     * @return $this
     */
    public function insert(Feed $entity)
    {
        $this->insert->insert($entity);
        if ($entity->getTypeId() !== Feed::FEED_ENTITY_SYSTEM_PUB) {
            $this->eventManager->trigger('feed', null, [
                'target_id' => $entity->getId(),
                'type_id'   => $entity->getTypeId(),
            ]);
        }


        return $this;
    }

    /**
     * Получить список записей
     *
     * @param Configuration $config
     * @return Response
     */
    public function getList(Configuration $config)
    {
        $collection = $this
            ->getTab($config)
            ->modify($config, $this->select->init())
                ->limit($config->getLimit() > 0 ? $config->getLimit() + 1 : null)
                ->offset($config->getOffset())
            ->getCollection();

        $hasMore = $config->getLimit() > 0 && $collection->count() > $config->getLimit();
        if ($hasMore) {
            $collection->pop();
        }

        return new Response($collection, $config->getOffset() + $collection->count(), $hasMore);
    }

    /**
     * Получить ссылку на закладку
     *
     * @param Configuration $config
     * @return Tab\FeedTabInterface|null
     */
    private function getTab(Configuration $config)
    {
        return $this->tabCollection->getByType($config->getTabType());
    }
}
