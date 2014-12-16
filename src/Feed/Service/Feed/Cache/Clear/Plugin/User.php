<?php

namespace Feed\Service\Feed\Cache\Clear\Plugin;

use Feed\Service\Feed\Cache\Adapter\Plugin\User as Adapter;
use Feed\Service\Feed\Cache\Clear\AbstractIdsEvent;

/**
 * Лента постов / Событие сброса кеша для владельца поста
 *
 * Class User
 * @package Feed\Service\FeedCache\FeedPlugin
 */
final class User extends AbstractIdsEvent
{
    /**
     * Получить ключ кеша
     *
     * @return string
     */
    public function getCacheKey()
    {
        return Adapter::getCacheKey($this->ids);
    }
}