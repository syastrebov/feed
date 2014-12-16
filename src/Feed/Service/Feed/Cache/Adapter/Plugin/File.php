<?php

namespace Feed\Service\Feed\Cache\Adapter\Plugin;

use Feed\Service\Feed\Cache\Adapter\AbstractAdapter;
use Feed\Service\Feed\Plugin\Adapter\FileInterface;
use Feed\Service\Feed\Plugin\Entity\FeedFileCollection;
use Feed\Service\Feed\Plugin\Adapter\Doctrine\File as Adapter;
use Doctrine\Common\Cache\Cache as CacheInterface;

/**
 * Лента постов / Получение файлов через кеш
 *
 * Class File
 * @package Feed\Service\Feed\Cache\Adapter\Plugin
 */
final class File extends AbstractAdapter implements FileInterface
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
     * Получить файлы по id постов
     *
     * @param array $ids
     * @return FeedFileCollection
     */
    public function getFilesByFeedIds(array $ids)
    {
        $cacheKey = self::getCacheKey($ids);
        if ($this->cache->contains($cacheKey)) {
            $result = $this->cache->fetch($cacheKey);
        } else {
            $result = $this->adapter->getFilesByFeedIds($ids);
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