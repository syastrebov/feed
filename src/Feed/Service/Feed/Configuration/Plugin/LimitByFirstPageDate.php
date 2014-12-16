<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\Configuration\Adapter\ConfigurationLimitByInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Плагин ограничения вывода ленты для правильной пагинации (с учетом добавления новых постов)
 *
 * Class LimitByFirstPageDate
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class LimitByFirstPageDate extends AbstractPlugin
{
    /**
     * @var ConfigurationLimitByInterface
     */
    private $adapter;

    /**
     * Constructor
     *
     * @param ConfigurationLimitByInterface $adapter
     */
    public function __construct(ConfigurationLimitByInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return ConfigurationPluginInterface::TYPE_LIMIT_BY_FIRST_PAGE_DATE;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->getOffsetFromQuery() === 0 || $this->getMaxIdFromQuery() > 0;
    }

    /**
     * Применить плагин
     *
     * @param Configuration $config
     * @return Configuration
     */
    public function apply(Configuration $config)
    {
        if ($this->getMaxIdFromQuery() > 0) {
            $config->setMaxId($this->getMaxIdFromQuery());
        }
        if ($this->getOffsetFromQuery() === 0) {
            $config->setMaxId($this->adapter->getId());
        }

        return $config;
    }
}