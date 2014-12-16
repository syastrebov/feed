<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;

/**
 * Лента постов / Фильтр по владельцу
 *
 * Class Owner
 * @package Application\Service\Feed\Filter
 */
final class Owner extends AbstractPlugin implements FeedFilterInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedFilterInterface::TYPE_OWNER;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return count($this->config->getOwnerIds()) > 0;
    }
}