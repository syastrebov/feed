<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Entity\Feed;
use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Social\Service\Like as LikeService;
use Like\Service\Like as VendorLikeService;
use Member\Entity\Member as MemberEntity;

/**
 * Лента постов / Плагин лайков для сущностей ленты
 *
 * Class Comment
 * @package Feed\Service\FeedPlugin
 */
final class Like extends AbstractPlugin
{
    /**
     * @var MemberEntity
     */
    private $identity;

    /**
     * @var LikeService
     */
    protected $likeService;

    /**
     * Constructor
     *
     * @param MemberEntity $identity
     * @param LikeService  $likeService
     */
    public function __construct(MemberEntity $identity, LikeService $likeService)
    {
        $this->identity    = $identity;
        $this->likeService = $likeService;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedContentInterface::TYPE_LIKE;
    }

    /**
     * Применить плагин к коллекции
     *
     * @param EntityCollection $collection
     * @return EntityCollection
     */
    public function apply(EntityCollection $collection)
    {
        $ids = $this->getFeedIds($collection);
        if ($this->identity->getId() > 0 && count($ids) > 0) {
            $response = $this->likeService->isLikedBunch(
                [
                    VendorLikeService::TYPE_SOCIAL_FEED_LIKE,
                    VendorLikeService::TYPE_SOCIAL_FEED_HELPFUL,
                ],
                $ids
            );

            if (is_array($response)) {
                foreach ($response as $like) {
                    foreach ($collection as $feed) {
                        /** @var Feed $feed */
                        if ($like['targetId'] == $feed->getId()) {
                            switch ($like['typeId']) {
                                case VendorLikeService::TYPE_SOCIAL_FEED_LIKE:
                                    $feed->setIsLiked((bool)$like['isLiked']);
                                    break;
                                case VendorLikeService::TYPE_SOCIAL_FEED_HELPFUL:
                                    $feed->setIsHelpful((bool)$like['isLiked']);
                                    break;
                                default:
                                    break;
                            }
                        }

                    }
                }
            }
        }

        return $collection;
    }

    /**
     * Применить плагин к отдельному объекту
     *
     * @param Feed $entity
     * @return mixed
     */
    public function applyEntity(Feed $entity)
    {
        $id = $this->identity->getId();
        if ($id > 0) {
            $entity->setIsLiked($this->likeService->isLiked(Feed::FEED_LIKE, $entity->getId(), $id));
            $entity->setIsHelpful($this->likeService->isLiked(Feed::FEED_HELPFUL, $entity->getId(), $id));
        }

        return $entity;
    }
}
