<?php

namespace Feed\Service\Feed\Plugin\Entity;

use Application\Entity\Tag as TagEntity;

/**
 * Лента постов / Теги поста загружаемые из плагина
 *
 * Class TagContentResponse
 * @package Feed\Service\FeedPlugin\Entity
 */
class FeedTag
{
    /**
     * @var int
     */
    private $feedId;

    /**
     * @var TagEntity
     */
    private $tag;

    /**
     * Constructor
     *
     * @param int       $feedId
     * @param TagEntity $tag
     */
    public function __construct($feedId, TagEntity $tag)
    {
        $this->feedId = (int)$feedId;
        $this->tag    = $tag;
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
     * Модель тега
     *
     * TagEntity
     */
    public function getTag()
    {
        return $this->tag;
    }
}