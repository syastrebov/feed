<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Плагин задания конфигурации для смещения
 *
 * Class Offset
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class Offset extends AbstractPlugin
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return ConfigurationPluginInterface::TYPE_OFFSET;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->getOffsetFromQuery() > 0;
    }

    /**
     * Применить плагин
     *
     * @param Configuration $config
     * @return Configuration
     */
    public function apply(Configuration $config)
    {
        return $config->setOffset($this->getOffsetFromQuery());
    }
} 