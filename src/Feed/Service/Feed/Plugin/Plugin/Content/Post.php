<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Entity\Feed as FeedEntity;
use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Feed\Service\Feed\Plugin\Adapter\PostInterface;

/**
 * Лента постов / Плагин подключения контента поста
 *
 * Class Post
 * @package Feed\Service\FeedPlugin\Plugin\Content
 */
final class Post extends AbstractPlugin
{
    /**
     * @var PostInterface
     */
    private $adapter;

    /**
     * Constructor
     *
     * @param PostInterface $adapter
     */
    public function __construct(PostInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedContentInterface::TYPE_POST;
    }

    /**
     * Применить плагин к коллекции
     *
     * @param EntityCollection $collection
     * @return EntityCollection
     */
    public function apply(EntityCollection $collection)
    {
        foreach ($collection as $entity) {
            $this->applyEntity($entity);
        }

        return $collection;
    }

    /**
     * Применить плагин к отдельному объекту
     *
     * @param FeedEntity $entity
     * @return FeedEntity
     */
    public function applyEntity(FeedEntity $entity)
    {
        $adapterEntities = $this->adapter->getPostsByIds([$entity->getId()]);
        if ($adapterEntities->count() > 0) {
            /** @var FeedEntity $adapterEntity */
            $adapterEntity = $adapterEntities->pop();
            $entity
                ->setTypeId($adapterEntity->getTypeId())
                ->setMemberId($adapterEntity->getMemberid())
                ->setEntryId($adapterEntity->getEntryId())
                ->setTitle($adapterEntity->getTitle())
                ->setText($adapterEntity->getText())
                ->setCreated($adapterEntity->getCreated())
                ->setUpdated($adapterEntity->getUpdated())
                ->setAccess($adapterEntity->getAccess())
                ->setCommentCount($adapterEntity->getCommentCount())
                ->setHelpfulCount($adapterEntity->getHelpfulCount())
                ->setIsDeleted($adapterEntity->getIsDeleted())
                ->resetTags()
                ->resetFiles();
        }

        return $entity;
    }
}
