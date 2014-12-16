<?php

namespace Feed\Service\Feed\Plugin\Adapter\Doctrine;

use Feed\Service\Feed\Plugin\Adapter\UserHiddenPostsInterface;
use Feed\Entity\FeedHidden;
use Exception;

/**
 * Лента постов / Получение скрытых постов пользователя
 *
 * Class UserHiddenPosts
 * @package Feed\Service\FeedPlugin\Plugin\Adapter\Doctrine
 */
final class UserHiddenPosts extends AbstractAdapter implements UserHiddenPostsInterface
{
    /**
     * Возвращает список скрытых постов пользователя
     *
     * @param int $userId
     * @return array
     * @throws Exception
     */
    public function getIds($userId)
    {
        if (!(is_int($userId) && $userId > 0)) {
            throw new Exception('Передан неверный userId');
        }

        $result = $this->entityManager
            ->getRepository('Feed\Entity\FeedHidden')
            ->findBy(['userId' => $userId]);

        $ids = [];
        foreach ($result as $hiddenEntity) {
            /** @var FeedHidden $hiddenEntity */
            $ids[] = $hiddenEntity->getFeedId();
        }

        return $ids;
    }
}
