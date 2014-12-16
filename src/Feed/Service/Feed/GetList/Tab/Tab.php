<?php

namespace Feed\Service\Feed\GetList\Tab;

use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Plugin\Filter\Collection as FilterCollection;
use Feed\Service\Feed\GetList\Plugin\Filter\FeedFilterInterface;
use Feed\Service\Feed\GetList\Plugin\Order\Collection as OrderCollection;
use Feed\Service\Feed\GetList\Plugin\Order\FeedOrderInterface;
use Exception;

/**
 * Лента постов / Контейнер фильтров для ленты
 *
 * Class AbstractTab
 * @package Application\Service\Feed\Tab
 */
class Tab implements FeedTabInterface
{
    /**
     * @var int
     */
    private $type;

    /**
     * @var FilterCollection
     */
    private $filterCollection;

    /**
     * @var OrderCollection
     */
    private $orderCollection;

    /**
     * Constructor
     *
     * @param int              $type
     * @param FilterCollection $filterCollection
     * @param OrderCollection  $orderCollection
     *
     * @throws Exception
     */
    public function __construct($type, FilterCollection $filterCollection, OrderCollection $orderCollection)
    {
        if (!$type) {
            throw new Exception('Передан недопустимый тип');
        }

        $this->type             = $type;
        $this->filterCollection = $filterCollection;
        $this->orderCollection  = $orderCollection;
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
     * Получить типы добавленных фильтров
     *
     * @return array
     */
    public function getFilterTypes()
    {
        return $this->filterCollection->getTypes();
    }

    /**
     * Получить типы добавленных сортировок
     *
     * @return array
     */
    public function getOrderTypes()
    {
        return $this->orderCollection->getTypes();
    }

    /**
     * Модифицировать запрос
     *
     * @param Configuration   $config
     * @param SelectInterface $select
     *
     * @return SelectInterface
     */
    public function modify(Configuration $config, SelectInterface $select)
    {
        foreach ($this->filterCollection as $filterPlugin) {
            /** @var FeedFilterInterface $filterPlugin */
            $filterPlugin->setConfiguration($config);

            if ($filterPlugin->shouldStart()) {
                $select = $filterPlugin->apply($select);
            }
        }
        foreach ($this->orderCollection as $orderPlugin) {
            /** @var FeedOrderInterface $orderPlugin */
            $orderPlugin->setConfiguration($config);

            if ($orderPlugin->shouldStart()) {
                $select = $orderPlugin->apply($select);
            }
        }

        return $select;
    }
}