<?php

namespace Feed\Service\Feed\Plugin\Adapter\Mock;

use Feed\Service\Feed\Plugin\Adapter\UserHiddenPostsInterface;

/**
 * Лента постов / Заглушка для получения скрытых постов пользователя
 *
 * Class UserHiddenPosts
 * @package Feed\Service\FeedPlugin\Plugin\Adapter\Mock
 */
final class UserHiddenPosts implements UserHiddenPostsInterface
{
    /**
     * @var array
     */
    private $feedIds;

    /**
     * Constructor
     *
     * @param array $feedIds
     */
    public function __construct(array $feedIds = [])
    {
        $this->feedIds = $feedIds;
    }

    /**
     * Задать id постов
     *
     * @param array $feedIds
     */
    public function setFeedIds(array $feedIds)
    {
        $this->feedIds = $feedIds;
    }

    /**
     * Возвращает список скрытых постов пользователя
     *
     * @param int $userId
     * @return array
     */
    public function getIds($userId)
    {
        return $this->feedIds;
    }
}