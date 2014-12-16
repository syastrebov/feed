<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\FeedPluginInterface;

/**
 * Лента постов / Интерфейс плагина фильтра
 *
 * Interface FeedFilterInterface
 * @package Application\Service\Feed\Filter
 */
interface FeedFilterInterface extends FeedPluginInterface
{
    const TYPE_CITY                       = 1;
    const TYPE_MONEY                      = 2;
    const TYPE_OWNER                      = 3;
    const TYPE_TAG_PRODUCT                = 4;
    const TYPE_TAG_NICHE                  = 5;
    const TYPE_IS_SIBERIA                 = 6;
    const TYPE_IS_DELETED                 = 7;
    const TYPE_LIMIT_BY_MAX_INTERVAL_DATE = 8;
    const TYPE_LIMIT_BY_FIRST_PAGE_DATE   = 9;
}