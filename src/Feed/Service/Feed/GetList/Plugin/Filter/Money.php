<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;

/**
 * Лента постов / Фильтр по доходу
 *
 * Class Money
 * @package Application\Service\Feed\Filter
 */
final class Money extends AbstractPlugin implements FeedFilterInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedFilterInterface::TYPE_MONEY;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->config->getMoneyMin() > 0 || $this->config->getMoneyMax() > 0;
    }
}