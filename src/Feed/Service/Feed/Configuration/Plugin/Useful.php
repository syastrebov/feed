<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Плагин задания конфигурации для сортировки по полезности
 *
 * Class Useful
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class Useful extends AbstractPlugin
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return ConfigurationPluginInterface::TYPE_USEFUL;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->getSortByFromQuery() === 'useful';
    }

    /**
     * Применить плагин
     *
     * @param Configuration $config
     * @return Configuration
     */
    public function apply(Configuration $config)
    {
        return $config->setSortUseful(true);
    }
}
