<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;

/**
 * Лента постов / Фильтр по сибирякам
 *
 * Class Siberia
 * @package Application\Service\Feed\Filter
 */
final class Siberia extends AbstractPlugin implements FeedFilterInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedFilterInterface::TYPE_IS_SIBERIA;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->config->getTabType() !== FeedTabInterface::TYPE_PROFILE;
    }
}