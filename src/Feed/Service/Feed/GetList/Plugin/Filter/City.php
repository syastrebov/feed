<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;

/**
 * Лента постов / Фильтр по городу
 *
 * Class City
 * @package Application\Service\Feed\Filter
 */
final class City extends AbstractPlugin implements FeedFilterInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedFilterInterface::TYPE_CITY;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->config->getCity() > 0;
    }
}