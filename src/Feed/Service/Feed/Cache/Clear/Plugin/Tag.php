<?php

namespace Feed\Service\Feed\Cache\Clear\Plugin;

use Feed\Service\Feed\Cache\Adapter\Plugin\Tag as Adapter;
use Feed\Service\Feed\Cache\Clear\AbstractIdsEvent;

/**
 * Лента постов / Событие сброса кеша для тегов поста
 *
 * Class Tag
 * @package Feed\Service\FeedCache\FeedPlugin
 */
final class Tag extends AbstractIdsEvent
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