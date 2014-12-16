<?php

namespace Feed\Service\Feed\GetList\Plugin;

use Feed\Service\Feed\GetList\Adapter;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Базовый плагин заглушки
 *
 * Class Mock
 * @package Application\Service\Feed\Plugin
 */
abstract class AbstractMockPlugin extends AbstractPlugin
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
     * @var bool
     */
    private $isApplied = false;

    /**
     * Constructor
     *
     * @param Adapter\Mock\Adapter $adapter
     * @param Configuration        $defaultConfig
     * @param int                  $type
     * @param bool                 $shouldStart
     */
    public function __construct(Adapter\Mock\Adapter $adapter, Configuration $defaultConfig, $type, $shouldStart)
    {
        parent::__construct($adapter, $defaultConfig);

        $this->type        = (int)$type;
        $this->shouldStart = (bool)$shouldStart;
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
     * Применить плагин
     *
     * @param Adapter\SelectInterface $select
     * @return Adapter\SelectInterface
     */
    public function apply(Adapter\SelectInterface $select)
    {
        $this->isApplied = true;
        return parent::apply($select);
    }

    /**
     * Вызван ли был обработчик
     *
     * @return bool
     */
    public function isApplied()
    {
        return $this->isApplied;
    }
} 