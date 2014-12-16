<?php

namespace Feed\Service\Feed\Plugin\Adapter;

use Feed\Service\Feed\GetList\Entity\Collection as FeedCollection;

/**
 * Лента постов / Интерфейс получения содержимого постов
 *
 * Interface PostInterface
 * @package Feed\Service\FeedPlugin\Plugin\Adapter
 */
interface PostInterface
{
    /**
     * Получить данные по постам по id
     *
     * @param array $ids
     * @return FeedCollection
     */
    public function getPostsByIds(array $ids);
}
