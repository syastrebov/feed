<?php

namespace Feed\Service\Feed\Cache\Clear;

/**
 * Лента постов / Интерфейс события сброса кеша
 *
 * Class FeedCacheEventInterface
 * @package Feed\Service\FeedCache
 */
interface FeedClearCacheEventInterface
{
    const EVENT_TYPE = 'feed.clear.cache';

    /**
     * Получить ключ кеша
     *
     * @return string
     */
    public function getCacheKey();
}