<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Заглушка для плагина сборки конфигурации
 *
 * Class Mock
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class Mock extends AbstractPlugin
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
     * @var Configuration
     */
    private $customConfig;

    /**
     * Constructor
     *
     * @param int           $type
     * @param bool          $shouldStart
     * @param Configuration $customConfig
     */
    public function __construct($type, $shouldStart, Configuration $customConfig = null)
    {
        $this->type         = (int)$type;
        $this->shouldStart  = (bool)$shouldStart;
        $this->customConfig = $customConfig;
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
     * @param Configuration $config
     * @return Configuration
     */
    public function apply(Configuration $config)
    {
        $this->isApplied = true;
        return $this->customConfig ? : $config;
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