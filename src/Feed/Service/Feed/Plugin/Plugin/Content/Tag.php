<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Entity\Feed as FeedEntity;
use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Feed\Service\Feed\Plugin\Entity\FeedTag as FeedTagEntity;
use Feed\Service\Feed\Plugin\Adapter\TagInterface;

/**
 * Лента постов / Плагин подключения тегов к посту
 *
 * Class Tag
 * @package Feed\Service\FeedPlugin\Plugin\Content
 */
final class Tag extends AbstractPlugin
{
    /**
     * @var TagInterface
     */
    private $adapter;

    /**
     * Constructor
     *
     * @param TagInterface $adapter
     */
    public function __construct(TagInterface $adapter)
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
        return FeedContentInterface::TYPE_TAG;
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
        $feedTagsCollection = $this->adapter->getTagsByFeedIds([$entity->getId()]);
        if ($feedTagsCollection->count() > 0) {
            foreach ($feedTagsCollection as $feedTagEntity) {
                /** @var FeedTagEntity $feedTagEntity */
                if ($entity->getId() === $feedTagEntity->getFeedId()) {
                    $entity->addTag($feedTagEntity->getTag());
                }
            }
        }

        return $entity;
    }
}
