<?php

namespace Feed\Service\Feed\Cache\Adapter\Plugin;

use Doctrine\Common\Cache\Cache as CacheInterface;
use Feed\Service\Feed\Cache\Adapter\AbstractAdapter;
use Feed\Service\Feed\Plugin\Entity\MemberCollection;
use Feed\Service\Feed\Plugin\Adapter\UserInterface;
use Feed\Service\Feed\Plugin\Adapter\Doctrine\User as Adapter;

/**
 * Лента постов / Получение владельца поста через кеш
 *
 * Class User
 * @package Feed\Service\FeedCache\Adapter\FeedPlugin
 */
final class User extends AbstractAdapter implements UserInterface
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
     * Получить пользователей по id
     *
     * @param array $ids
     * @return MemberCollection
     */
    public function getMembersByIds(array $ids)
    {
        $cacheKey = self::getCacheKey($ids);
        if ($this->cache->contains($cacheKey)) {
            $result = $this->cache->fetch($cacheKey);
        } else {
            $result = $this->adapter->getMembersByIds($ids);
            $this->cache->save($cacheKey, $result, 3600);
        }

        return $result;
    }

    /**
     * Ключ для кеша
     *
     * @param array $ids
     * @return string
     */
    public static function getCacheKey(array $ids)
    {
        return md5(__CLASS__ . implode(',', $ids));
    }
}