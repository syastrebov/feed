<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;

/**
 * Лента постов / Плагин задания конфигурации для выбора типа вкладки
 *
 * Class TabType
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class TabType extends AbstractPlugin
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return ConfigurationPluginInterface::TYPE_TAB_TYPE;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return in_array($this->getTabTypeFromQuery(), [
            FeedTabInterface::TYPE_ALL,
            FeedTabInterface::TYPE_MY,
            FeedTabInterface::TYPE_PROFILE,
            FeedTabInterface::TYPE_PRODUCT,
            FeedTabInterface::TYPE_NICHE,
        ], true);
    }

    /**
     * Применить плагин
     *
     * @param Configuration $config
     * @return Configuration
     */
    public function apply(Configuration $config)
    {
        return $config->setTabType($this->getTabTypeFromQuery());
    }
}