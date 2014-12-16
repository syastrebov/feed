<?php

namespace Feed\Service\Feed\Configuration\Adapter\Mock;

use Doctrine\Common\Collections\ArrayCollection;
use Feed\Service\Feed\Configuration\Adapter\SubscriberInterface;

/**
 * Лента постов / Заглушка для получения объектов пользователя
 *
 * Class Member
 * @package Feed\Service\Feed\Adapter\Mock
 */
class Subscriber implements SubscriberInterface
{
    /**
     * @var ArrayCollection
     */
    private $subscribers;

    /**
     * Constructor
     *
     * @param ArrayCollection $subscribers
     */
    public function __construct(ArrayCollection $subscribers = null)
    {
        $this->subscribers = $subscribers ? : new ArrayCollection();
    }

    /**
     * Получить список подписчиков
     *
     * @param int $memberId
     * @return ArrayCollection
     */
    public function getSubscribers($memberId)
    {
        return $this->subscribers;
    }
}
