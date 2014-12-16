<?php

namespace Feed\Service\Feed\GetList\Plugin;

use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Интерфейс плагина ленты
 *
 * Interface FeedPluginInterface
 * @package Application\Service\Feed\Plugin
 */
interface FeedPluginInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType();

    /**
     * Задать конфигурацию
     *
     * @param Configuration $config
     * @return $this
     */
    public function setConfiguration(Configuration $config);

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart();

    /**
     * Применить плагин
     *
     * @param SelectInterface $select
     * @return SelectInterface
     */
    public function apply(SelectInterface $select);
}
