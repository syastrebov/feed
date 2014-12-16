<?php

namespace Feed\Service\Feed\Plugin\Plugin;

use Feed\Service\Feed\GetList\Entity\Collection;

/**
 * Лента постов / Интерфейс плагинов ленты
 *
 * Interface FeedPluginInterface
 * @package Feed\Service\FeedPlugin
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
     * Применить плагин к коллекции
     *
     * @param Collection $collection
     * @return Collection
     */
    public function apply(Collection $collection);
}
