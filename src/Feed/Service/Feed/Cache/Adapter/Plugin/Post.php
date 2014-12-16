<?php

namespace Feed\Service\Feed\Cache\Adapter\Plugin;

use Feed\Service\Feed\Cache\Adapter\AbstractAdapter;
use Feed\Service\Feed\Plugin\Adapter\PostInterface;
use Feed\Service\Feed\GetList\Entity\Collection as FeedCollection;
use Feed\Service\Feed\Plugin\Adapter\Doctrine\Post as Adapter;
use Doctrine\Common\Cache\Cache as CacheInterface;

/**
 * Лента постов / Получение содержимого поста через кеш
 *
 * Class Post
 * @package Feed\Service\FeedCache\Adapter\FeedPlugin
 */
final class Post extends AbstractAdapter implements PostInterface
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
     * Получить данные по постам по id
     *
     * @param array $ids
     * @return FeedCollection
     */
    public function getPostsByIds(array $ids)
    {
        $cacheKey = self::getCacheKey($ids);
        if ($this->cache->contains($cacheKey)) {
            $result = $this->cache->fetch($cacheKey);
        } else {
            $result = $this->adapter->getPostsByIds($ids);
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