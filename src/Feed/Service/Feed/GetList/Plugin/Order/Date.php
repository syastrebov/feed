<?php

namespace Feed\Service\Feed\GetList\Plugin\Order;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;

/**
 * Лента постов / Сортировка по дате
 *
 * Class Date
 * @package Application\Service\Feed\Order
 */
final class Date extends AbstractPlugin implements FeedOrderInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedOrderInterface::TYPE_DATE;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->config->isSortUseful() == false;
    }
}