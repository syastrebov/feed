<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;

/**
 * Лента постов / Фильтр по тегу выбора курса
 *
 * Class TagProduct
 * @package Application\Service\Feed\Filter
 */
final class TagProduct extends AbstractPlugin implements FeedFilterInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedFilterInterface::TYPE_TAG_PRODUCT;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return count($this->config->getProductTags()) > 0;
    }
} 