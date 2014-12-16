<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Плагин задания конфигурации админства
 *
 * Class Admin
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class Admin extends AbstractPlugin
{
    /**
     * @var int
     */
    private $isAdmin;

    /**
     * Constructor
     *
     * @param int $isAdmin
     */
    public function __construct($isAdmin)
    {
        $this->isAdmin = $isAdmin;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return ConfigurationPluginInterface::TYPE_ADMIN;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return true;
    }

    /**
     * Применить плагин
     *
     * @param Configuration $config
     * @return Configuration
     */
    public function apply(Configuration $config)
    {
        return $config->setAsAdmin($this->isAdmin);
    }
}
