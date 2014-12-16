<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use BmComment\Service\Comment as CommentService;
use Feed\Entity\Feed;
use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;

/**
 * Лента постов / Плагин комментариев для сущностей ленты
 * Подгружает комментарии в ленту, пересчитывает кол-во комментов для модераторов
 *
 * Class Comment
 * @package Feed\Service\FeedPlugin
 */
final class Comment implements FeedContentInterface
{
    /**
     * @var CommentService
     */
    private $commentService;

    private $isAdmin;

    /**
     * Constructor
     *
     * @param CommentService $commentService
     * @param bool           $isAdmin
     */
    public function __construct(CommentService $commentService, $isAdmin)
    {
        $this->commentService  = $commentService;
        $this->isAdmin         = (bool)$isAdmin;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedContentInterface::TYPE_COMMENT;
    }

    /**
     * Применить плагин к коллекции
     *
     * @param EntityCollection $collection
     * @return EntityCollection
     */
    public function apply(EntityCollection $collection)
    {
        $newCollection = new EntityCollection();
        /** @var Feed $feed */
        foreach ($collection as $feed) {
            $feed = $this->iterateFeed($feed, 1);
            $newCollection->attach($feed);
        }

        return $newCollection;
    }

    /**
     * Применить плагин к отдельному объекту
     *
     * @param Feed $entity
     * @return mixed
     */
    public function applyEntity(Feed $entity)
    {
        return $this->iterateFeed($entity);
    }

    /**
     * Обработать ону сущность поста
     *
     * @param Feed $feed
     * @param int  $commentLimit
     *
     * @return Feed
     */
    protected function iterateFeed(Feed $feed, $commentLimit = 3)
    {
        $this->commentService
            ->setTarget($feed::FEED_COMMENT, $feed->getId())
            ->setLimit($commentLimit);

        if ($this->isAdmin) {
            $feed->setCommentCount($this->commentService->getCommentsCount());
        }

        $feed->setComments($this->commentService->getComments());
        return $feed;
    }
}
