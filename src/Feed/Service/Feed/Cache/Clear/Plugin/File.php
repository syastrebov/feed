<?php

namespace Feed\Service\Feed\Cache\Clear\Plugin;

use Feed\Service\Feed\Cache\Adapter\Plugin\File as Adapter;
use Feed\Service\Feed\Cache\Clear\AbstractIdsEvent;

/**
 * Лента постов / Событие сброса кеша для файлов поста
 *
 * Class File
 * @package Feed\Service\Feed\Cache\Clear\Plugin
 */
final class File extends AbstractIdsEvent
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