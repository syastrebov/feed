<?php

namespace Feed\Service\Feed\Cache\Clear\Plugin;

use Feed\Service\Feed\Cache\Adapter\Plugin\Post as Adapter;
use Feed\Service\Feed\Cache\Clear\AbstractIdsEvent;

/**
 * Лента постов / Событие сброса кеша для содержимого поста
 *
 * Class Post
 * @package Feed\Service\FeedCache\FeedPlugin
 */
final class Post extends AbstractIdsEvent
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