<?php

namespace Feed\Service\Feed\Cache\Clear;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Doctrine\Common\Cache\Cache as CacheInterface;

/**
 * Лента постов / Слушатель для очищения кеша
 *
 * Class Listener
 * @package Feed\Service\FeedCache\Clear
 */
class Listener extends AbstractListenerAggregate
{
    /**
     * @var \Doctrine\Common\Cache\Cache
     */
    private $cache;

    /**
     * Constructor
     *
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    /**
     * Вешаем слушатели
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->getSharedManager()->attach('*', FeedClearCacheEventInterface::EVENT_TYPE, [$this, 'handler']);
    }

    /**
     * Обработчик сброса кеша
     *
     * @param FeedClearCacheEventInterface $event
     */
    public function handler(FeedClearCacheEventInterface $event)
    {
        $this->cache->delete($event->getCacheKey());
    }
}