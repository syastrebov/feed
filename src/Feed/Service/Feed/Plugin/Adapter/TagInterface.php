<?php

namespace Feed\Service\Feed\Plugin\Adapter;

use Feed\Service\Feed\Plugin\Entity\FeedTagCollection;

/**
 * Лента постов / Интерфейс получения тегов
 *
 * Interface FeedTagsInterface
 * @package Feed\Service\FeedPlugin\Plugin\Adapter
 */
interface TagInterface
{
    /**
     * Получить теги по id постов
     *
     * @param array $ids
     * @return FeedTagCollection
     */
    public function getTagsByFeedIds(array $ids);
}