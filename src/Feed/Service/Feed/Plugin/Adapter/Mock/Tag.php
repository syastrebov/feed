<?php

namespace Feed\Service\Feed\Plugin\Adapter\Mock;

use Doctrine\Common\Collections\ArrayCollection;
use Feed\Service\Feed\Plugin\Entity\FeedTagCollection;
use Feed\Service\Feed\Plugin\Adapter\TagInterface;

/**
 * Лента постов / Заглушка для получения тегов
 *
 * Class Tag
 * @package Feed\Service\FeedPlugin\Plugin\Adapter\Mock
 */
final class Tag implements TagInterface
{
    /**
     * @var ArrayCollection
     */
    private $feedTags;

    /**
     * Задать теги
     *
     * @param FeedTagCollection $feedTags
     * @return $this
     */
    public function setFeedTags(FeedTagCollection $feedTags)
    {
        $this->feedTags = $feedTags;
        return $this;
    }

    /**
     * Получить теги по id постов
     *
     * @param array $ids
     * @return ArrayCollection
     */
    public function getTagsByFeedIds(array $ids)
    {
        return $this->feedTags;
    }
}
