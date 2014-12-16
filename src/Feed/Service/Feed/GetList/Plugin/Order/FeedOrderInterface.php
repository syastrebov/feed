<?php

namespace Feed\Service\Feed\GetList\Plugin\Order;

use Feed\Service\Feed\GetList\Plugin\FeedPluginInterface;

/**
 * Лента постов / Интерфейс плагина сортировки
 *
 * Interface FeedOrderInterface
 * @package Application\Service\Feed\Order
 */
interface FeedOrderInterface extends FeedPluginInterface
{
    const TYPE_DATE   = 1;
    const TYPE_USEFUL = 2;
}