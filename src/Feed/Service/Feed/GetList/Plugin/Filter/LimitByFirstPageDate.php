<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;

/**
 * Лента постов / Фильтр ограничения вывода ленты для правильной пагинации (с учетом добавления новых постов)
 *
 * Class LimitByFirstPageDate
 * @package Feed\Service\Feed\Plugin\Filter
 */
final class LimitByFirstPageDate extends AbstractPlugin implements FeedFilterInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedFilterInterface::TYPE_LIMIT_BY_FIRST_PAGE_DATE;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->config->getMaxId() > 0;
    }
}