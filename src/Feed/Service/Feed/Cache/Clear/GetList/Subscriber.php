<?php

namespace Feed\Service\Feed\Cache\Clear\GetList;

use Feed\Service\Feed\Cache\Clear\AbstractIdEvent;
use Feed\Service\Feed\Cache\Adapter\GetList\Subscriber as Adapter;

/**
 * Лента постов / Событие сброса кеша подписчиков пользователя
 *
 * Class Subscriber
 * @package Feed\Service\Feed\Cache\Clear\GetList
 */
final class Subscriber extends AbstractIdEvent
{
    /**
     * Получить ключ кеша
     *
     * @return string
     */
    public function getCacheKey()
    {
        return Adapter::getCacheKey($this->id);
    }
}