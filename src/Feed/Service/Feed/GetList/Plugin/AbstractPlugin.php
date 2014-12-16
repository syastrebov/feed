<?php

namespace Feed\Service\Feed\GetList\Plugin;

use Feed\Service\Feed\GetList\Adapter\AdapterInterface;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Базовый плагин
 *
 * Class AbstractFilter
 * @package Application\Service\Feed\Filter
 */
abstract class AbstractPlugin implements FeedPluginInterface
{
    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * @var Configuration
     */
    protected $config;

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
     * @param Configuration $defaultConfig
     */
    public function __construct(AdapterInterface $adapter, Configuration $defaultConfig)
    {
        $this->adapter = $adapter;
        $this->config  = clone $defaultConfig;
    }

    /**
     * Задать конфигурацию
     *
     * @param Configuration $config
     * @return $this
     */
    public function setConfiguration(Configuration $config)
    {
        $this->config = clone $config;
        return $this;
    }

    /**
     * Применить плагин
     *
     * @param SelectInterface $select
     * @return SelectInterface
     */
    public function apply(SelectInterface $select)
    {
        return $this->adapter->apply($select, $this->config);
    }
}