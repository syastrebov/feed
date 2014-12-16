<?php

namespace Feed\Service\Feed\Cache\Adapter\Plugin;

use Doctrine\Common\Cache\Cache as CacheInterface;
use Feed\Service\Feed\Cache\Adapter\AbstractAdapter;
use Feed\Service\Feed\Plugin\Adapter\Doctrine\UserHiddenPosts as Adapter;
use Feed\Service\Feed\Plugin\Adapter\UserHiddenPostsInterface;

/**
 * Лента постов / Получение скрытых постов пользователя через кеш
 *
 * Class UserHiddenPosts
 * @package Feed\Service\FeedCache\Adapter\FeedPlugin
 */
final class UserHiddenPosts extends AbstractAdapter implements UserHiddenPostsInterface
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
     * Возвращает список скрытых постов пользователя
     *
     * @param int $userId
     * @return array
     */
    public function getIds($userId)
    {
        $cacheKey = self::getCacheKey($userId);
        if ($this->cache->contains($cacheKey)) {
            $result = $this->cache->fetch($cacheKey);
        } else {
            $result = $this->adapter->getIds($userId);
            $this->cache->save($cacheKey, $result, 3600);
        }

        return $result;
    }

    /**
     * Ключ для кеша
     *
     * @param int $userId
     * @return string
     */
    public static function getCacheKey($userId)
    {
        return md5(__CLASS__ . $userId);
    }
}