<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\Configuration\Adapter\ConfigurationLimitByInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;

/**
 * Лента постов / Плагин ограничения вывода ленты по дате за максимальный период
 *
 * Class LimitByDate
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class LimitByMaxIntervalDate extends AbstractPlugin
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
        return ConfigurationPluginInterface::TYPE_LIMIT_BY_MAX_INTERVAL_DATE;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->getTabTypeFromQuery() !== FeedTabInterface::TYPE_PROFILE;
    }

    /**
     * Применить плагин
     *
     * @param Configuration $config
     * @return Configuration
     */
    public function apply(Configuration $config)
    {
        return $config->setMinId($this->adapter->getId());
    }
}