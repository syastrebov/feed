<?php

namespace Feed\Service\Feed\Configuration\Adapter;

/**
 * Лента постов / Интерфес репозитория пользователей
 *
 * Interface MemberInterface
 * @package Feed\Service\Feed\Adapter
 */
interface SubscriberInterface
{
    /**
     * Получить список подписчиков
     *
     * @param int $memberId
     * @return array
     */
    public function getSubscribers($memberId);
}
