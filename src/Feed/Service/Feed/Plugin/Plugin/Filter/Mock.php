<?php

namespace Feed\Service\Feed\Plugin\Plugin\Filter;

use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;

/**
 * Лента постов / Заглушка для фильтра ленты
 *
 * Class Mock
 * @package Feed\Service\FeedPlugin\Plugin\Filter
 */
class Mock implements FeedFilterInterface
{
    /**
     * @var int
     */
    private $type;

    /**
     * @var bool
     */
    private $shouldStart;

    /**
     * @var \Feed\Service\Feed\GetList\Entity\Collection
     */
    private $newCollection;

    /**
     * Constructor
     *
     * @param int              $type
     * @param bool             $shouldStart
     * @param EntityCollection $newCollection
     * @param
     */
    public function __construct($type, $shouldStart, EntityCollection $newCollection = null)
    {
        $this->type          = (int)$type;
        $this->shouldStart   = (bool)$shouldStart;
        $this->newCollection = $newCollection;
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
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->shouldStart;
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
}