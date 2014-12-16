<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Entity\Feed;
use Feed\Service\Feed\Plugin\Plugin\FeedPluginInterface;

/**
 * Лента постов / Интерфейс сборки контента для постов
 *
 * Interface FeedContentInterface
 * @package Feed\Service\FeedPlugin\Plugin
 */
interface FeedContentInterface extends FeedPluginInterface
{
    const TYPE_POST           = 1;
    const TYPE_COMMENT        = 2;
    const TYPE_LIKE           = 3;
    const TYPE_USER           = 4;
    const TYPE_USER_SUBSCRIBE = 5;
    const TYPE_TAG            = 6;
    const TYPE_FILE           = 7;

    /**
     * Применить плагин к отдельному объекту
     *
     * @param Feed $entity
     * @return Feed
     */
    public function applyEntity(Feed $entity);
}
