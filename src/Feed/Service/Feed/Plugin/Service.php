<?php

namespace Feed\Service\Feed\Plugin;

use Feed\Entity\Feed;
use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Feed\Service\Feed\Plugin\Plugin\Content\Collection as ContentCollection;
use Feed\Service\Feed\Plugin\Plugin\Content\FeedContentInterface;
use Feed\Service\Feed\Plugin\Plugin\Filter\Collection as FilterCollection;
use Feed\Service\Feed\Plugin\Plugin\Filter\FeedFilterInterface;

/**
 * Лента постов / Сервис применения плагинов
 *
 * Class Service
 * @package Feed\Service\FeedPlugin
 */
class Service
{
    /**
     * @var FilterCollection
     */
    protected $filterCollection;

    /**
     * @var ContentCollection
     */
    protected $contentCollection;

    /**
     * Constructor
     *
     * @param FilterCollection  $filterCollection
     * @param ContentCollection $contentCollection
     */
    public function __construct(FilterCollection $filterCollection, ContentCollection $contentCollection)
    {
        $this->filterCollection  = $filterCollection;
        $this->contentCollection = $contentCollection;
    }

    /**
     * Применить плагин к коллекции постов
     *
     * @param EntityCollection $entityCollection
     * @return EntityCollection
     */
    public function apply(EntityCollection $entityCollection)
    {
        /** @var FeedContentInterface $plugin */
        foreach ($this->contentCollection as $plugin) {
            $entityCollection = $plugin->apply($entityCollection);
        }
        /** @var FeedFilterInterface $plugin */
        foreach ($this->filterCollection as $plugin) {
            if ($plugin->shouldStart()) {
                $entityCollection = $plugin->apply($entityCollection);
            }
        }

        return $entityCollection;
    }

    /**
     * Применить плагины к определенному посту
     *
     * @param Feed $feed
     * @return Feed
     */
    public function applyEntity(Feed $feed)
    {
        /** @var Plugin\Content\FeedContentInterface $plugin */
        foreach ($this->contentCollection as $plugin) {
            $feed = $plugin->applyEntity($feed);
        }

        return $feed;
    }
}
