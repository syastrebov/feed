<?php

namespace Feed\Service\Feed\GetList\Tab;

use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Интерфейс плагина типа ленты
 *
 * Interface FeedTabInterface
 * @package Application\Service\Feed\Tab
 */
interface FeedTabInterface
{
    const TYPE_ALL     = 1;
    const TYPE_MY      = 2;
    const TYPE_PROFILE = 3;
    const TYPE_NICHE   = 4;
    const TYPE_PRODUCT = 5;

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType();

    /**
     * Модифицировать запрос
     *
     * @param Configuration   $config
     * @param SelectInterface $select
     *
     * @return SelectInterface
     */
    public function modify(Configuration $config, SelectInterface $select);
} 