<?php

namespace Feed\Service\Feed\Plugin\Adapter;

use Feed\Service\Feed\Plugin\Entity\MemberCollection;

/**
 * Лента постов / Интерфейс получения модели пользователя
 *
 * Interface UserInterface
 * @package Feed\Service\FeedPlugin\Plugin\Adapter
 */
interface UserInterface
{
    /**
     * Получить пользователей по id
     *
     * @param array $ids
     * @return MemberCollection
     */
    public function getMembersByIds(array $ids);
}