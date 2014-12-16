<?php

namespace Feed\Service\Feed\GetList\Plugin\Order;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;

/**
 * Лента постов / Сортировка по полезности
 *
 * Class Useful
 * @package Application\Service\Feed\Order
 */
final class Useful extends AbstractPlugin implements FeedOrderInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedOrderInterface::TYPE_USEFUL;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->config->isSortUseful() == true;
    }
}