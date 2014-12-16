<?php

namespace Feed\Service\Feed\Cache\Adapter;

use Doctrine\Common\Cache\Cache as CacheInterface;

/**
 * Лента постов / Базовый адаптер для кеша
 *
 * Class AbstractAdapter
 * @package Feed\Service\Feed\Cache\Adapter\Plugin
 */
abstract class AbstractAdapter
{
    /**
     * @var CacheInterface
     */
    protected $cache;

    /**
     * Constructor
     *
     * @param CacheInterface $cache
     */
    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }
}