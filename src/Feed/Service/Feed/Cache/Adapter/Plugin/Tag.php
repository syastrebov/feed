<?php

namespace Feed\Service\Feed\Cache\Adapter\Plugin;

use Feed\Service\Feed\Cache\Adapter\AbstractAdapter;
use Feed\Service\Feed\Plugin\Entity\FeedTagCollection;
use Feed\Service\Feed\Plugin\Adapter\Doctrine\Tag as Adapter;
use Doctrine\Common\Cache\Cache as CacheInterface;
use Feed\Service\Feed\Plugin\Adapter\TagInterface;

/**
 * Лента постов / Получение тегов поста через кеш
 *
 * Class Tag
 * @package Feed\Service\FeedCache\Adapter\FeedPlugin
 */
final class Tag extends AbstractAdapter implements TagInterface
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
     * Получить теги по id постов
     *
     * @param array $ids
     * @return FeedTagCollection
     */
    public function getTagsByFeedIds(array $ids)
    {
        $cacheKey = self::getCacheKey($ids);
        if ($this->cache->contains($cacheKey)) {
            $result = $this->cache->fetch($cacheKey);
        } else {
            $result = $this->adapter->getTagsByFeedIds($ids);
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