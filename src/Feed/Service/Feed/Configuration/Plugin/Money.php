<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Плагин задания конфигурации для фильтрации по доходу
 *
 * Class Money
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class Money extends AbstractPlugin
{
    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return ConfigurationPluginInterface::TYPE_MONEY;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        if (!$this->getMoneyMinFromQuery() && !$this->getMoneyMaxFromQuery()) {
            return false;
        }
        if ($this->getMoneyMaxFromQuery() > 0 && $this->getMoneyMinFromQuery() > 0) {
            return $this->getMoneyMaxFromQuery() > $this->getMoneyMinFromQuery();
        }

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
        if ($this->getMoneyMinFromQuery() > 0) {
            $config->setMoneyMin($this->getMoneyMinFromQuery());
        }
        if ($this->getMoneyMaxFromQuery() > 0) {
            $config->setMoneyMax($this->getMoneyMaxFromQuery());
        }

        return $config;
    }
}