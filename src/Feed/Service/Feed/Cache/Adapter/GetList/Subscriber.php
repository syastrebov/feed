<?php

namespace Feed\Service\Feed\Cache\Adapter\GetList;

use Feed\Service\Feed\Configuration\Adapter\SubscriberInterface;
use Feed\Service\Feed\Configuration\Adapter\Doctrine\Subscriber as Adapter;
use Feed\Service\Feed\Cache\Adapter\AbstractAdapter;
use Doctrine\Common\Cache\Cache as CacheInterface;

/**
 * Лента постов / Получение подписчиков пользователя через кеш
 *
 * Class Subscriber
 * @package Feed\Service\FeedCache\Adapter\GetList
 */
final class Subscriber extends AbstractAdapter implements SubscriberInterface
{
    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * Constructor
     *
     * @param CacheInterface $cache
     * @param Adapter        $adapter
     */
    public function __construct(CacheInterface $cache, Adapter $adapter)
    {
        parent::__construct($cache);
        $this->adapter = $adapter;
    }

    /**
     * Получить список подписчиков
     *
     * @param int $memberId
     * @return array
     */
    public function getSubscribers($memberId)
    {
        $cacheKey = self::getCacheKey($memberId);
        if ($this->cache->contains($cacheKey)) {
            $result = $this->cache->fetch($cacheKey);
        } else {
            $result = $this->adapter->getSubscribers($memberId);
            $this->cache->save($cacheKey, $result, 3600);
        }

        return $result;
    }

    /**
     * Ключ для кеша
     *
     * @param int $memberId
     * @return string
     */
    public static function getCacheKey($memberId)
    {
        return md5(__CLASS__ . $memberId);
    }
}
