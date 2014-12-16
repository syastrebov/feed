<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;

/**
 * Лента постов / Плагин задания конфигурации для выбора курса
 *
 * Class TagProduct
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class TagProduct extends AbstractPlugin
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return ConfigurationPluginInterface::TYPE_TAG_PRODUCT;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->getTagProductFromQuery() > 0 && in_array($this->getTabTypeFromQuery(), [
            FeedTabInterface::TYPE_ALL,
            FeedTabInterface::TYPE_PRODUCT,
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
        return $config->setProductTags([$this->getTagProductFromQuery()]);
    }
}