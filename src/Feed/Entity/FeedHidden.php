<?php

namespace Feed\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Лента постов / Пользователь скрыл публикации
 *
 * @ORM\Entity
 * @ORM\Table(name="feed_hidden")
 */
class FeedHidden
{
    /**
     * @ORM\Id
     * @ORM\Column(name="feed_id", type="integer")
     */
    protected $feedId;

    /**
     * @ORM\Id
     * @ORM\Column(name="user_id", type="integer")
     */
    protected $userId;

    /**
     * @var \DateTime
     * @ORM\Column(name="created", type="datetime")
     */
    protected $created;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->created = new DateTime();
    }

    /**
     * Id поста
     *
     * @param int $feedId
     * @return $this
     */
    public function setFeedId($feedId)
    {
        $this->feedId = (int)$feedId;
        return $this;
    }

    /**
     * Id поста
     *
     * @return int
     */
    public function getFeedId()
    {
        return $this->feedId;
    }

    /**
     * Id пользователя
     *
     * @param int $userId
     * @return $this
     */
    public function setUserId($userId)
    {
        $this->userId = (int)$userId;
        return $this;
    }

    /**
     * Id пользователя
     *
     * @return int
     */
    public function getUserId()
    {
        return $this->userId;
    }
}