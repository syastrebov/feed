<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\AbstractPlugin;

/**
 * Лента постов / Фильтр по тегу выбора нишы
 *
 * Class Tag
 * @package Application\Service\Feed\Filter
 */
final class TagNiche extends AbstractPlugin implements FeedFilterInterface
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedFilterInterface::TYPE_TAG_NICHE;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return count($this->config->getNicheTags()) > 0;
    }
} 