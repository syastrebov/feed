<?php

namespace Feed\Service\Feed\Plugin\Plugin\Filter;

use Feed\Service\Feed\Plugin\Plugin\FeedPluginInterface;

/**
 * Лента постов / Интерфейс фильтров ленты
 *
 * Interface FeedFilterInterface
 * @package Feed\Service\FeedPlugin\Plugin
 */
interface FeedFilterInterface extends FeedPluginInterface
{
    const TYPE_I_DONT_WANT_TO_SEE_THIS = 1;

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart();
}
