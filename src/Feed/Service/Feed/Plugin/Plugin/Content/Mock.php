<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Feed\Entity\Feed;

/**
 * Лента постов / Заглушка для фильтра ленты
 *
 * Class Mock
 * @package Feed\Service\FeedPlugin\Plugin\Filter
 */
class Mock implements FeedContentInterface
{
    /**
     * @var int
     */
    private $type;

    /**
     * @var \Feed\Service\Feed\GetList\Entity\Collection
     */
    private $newCollection;

    /**
     * @var Feed
     */
    private $newEntity;

    /**
     * Constructor
     *
     * @param int              $type
     * @param EntityCollection $newCollection
     * @param Feed             $newEntity
     */
    public function __construct($type, EntityCollection $newCollection = null, Feed $newEntity = null)
    {
        $this->type          = (int)$type;
        $this->newCollection = $newCollection;
        $this->newEntity     = $newEntity;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Применить плагин к коллекции
     *
     * @param EntityCollection $collection
     * @return EntityCollection
     */
    public function apply(EntityCollection $collection)
    {
        return $this->newCollection ? : $collection;
    }

    /**
     * Применить плагин к отдельному объекту
     *
     * @param Feed $entity
     * @return mixed
     */
    public function applyEntity(Feed $entity)
    {
        return $this->newEntity ? : $entity;
    }
}