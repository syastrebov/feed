<?php

namespace Feed\Service\Feed\Cache\Clear\Plugin;

use Feed\Service\Feed\Cache\Clear\AbstractIdEvent;
use Feed\Service\Feed\Cache\Adapter\Plugin\UserHiddenPosts as Adapter;

/**
 * Лента постов / Событие сброса кеша для скрытых постов
 *
 * Class UserHiddenPosts
 * @package Feed\Service\FeedCache\FeedPlugin
 */
final class UserHiddenPosts extends AbstractIdEvent
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