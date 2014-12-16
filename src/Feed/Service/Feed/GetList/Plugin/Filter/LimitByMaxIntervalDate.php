<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;

/**
 * Лента постов / Фильтр ограничения вывода ленты по дате за максимальный период
 *
 * Class LimitByDate
 * @package Feed\Service\Feed\Plugin\Filter
 */
final class LimitByMaxIntervalDate extends AbstractPlugin implements FeedFilterInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedFilterInterface::TYPE_LIMIT_BY_MAX_INTERVAL_DATE;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->config->getMinId() > 0;
    }
}