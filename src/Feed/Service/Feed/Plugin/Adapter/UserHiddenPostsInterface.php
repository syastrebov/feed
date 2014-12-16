<?php

namespace Feed\Service\Feed\Plugin\Adapter;

/**
 * Лента постов / Интерфейс получения скрытых постов пользователя
 *
 * Interface UserHiddenPostsInterface
 * @package Feed\Service\FeedPlugin\Plugin\Adapter
 */
interface UserHiddenPostsInterface
{
    /**
     * Возвращает список скрытых постов пользователя
     *
     * @param int $userId
     * @return array
     */
    public function getIds($userId);
}