<?php

namespace Feed\Service\Feed\Plugin\Adapter\Mock;

use Feed\Service\Feed\GetList\Entity\Collection as FeedCollection;
use Feed\Service\Feed\Plugin\Adapter\PostInterface;

/**
 * Лента постов / Заглушка для получения содержимого постов
 *
 * Class Post
 * @package Feed\Service\FeedPlugin\Plugin\Adapter\Mock
 */
final class Post implements PostInterface
{
    /**
     * Получить данные по постам по id
     *
     * @param array $ids
     * @return FeedCollection
     */
    public function getPostsByIds(array $ids)
    {
        return new FeedCollection();
    }
}
