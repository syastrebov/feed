<?php

namespace Feed\Service\Feed\Configuration\Adapter\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use Feed\Service\Feed\Configuration\Adapter\SubscriberInterface;
use Member\Service\Subscription as SubscriptionService;

/**
 * Лента постов / Обертка для получения пользователей черед Doctrine ORM
 *
 * Class Member
 * @package Feed\Service\Feed\Adapter\Doctrine
 */
final class Subscriber implements SubscriberInterface
{
    /**
     * @var SubscriptionService
     */
    private $subscriptionService;

    /**
     * Constructor
     *
     * @param SubscriptionService $subscriptionService
     */
    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    /**
     * Получить список подписчиков
     *
     * @param int $memberId
     * @return array
     */
    public function getSubscribers($memberId)
    {
        $subscribers = [];

        $limit  = 1000;
        $offset = 0;

        do {
            /** @var ArrayCollection $result */
            $result = $this->subscriptionService->findMySubscribers($memberId, $limit, $offset);
            $offset += $limit;

            foreach ($result as $subscriber) {
                $subscribers[] = $subscriber;
            }

        } while (count($result) > 0);

        return $subscribers;
    }
}
