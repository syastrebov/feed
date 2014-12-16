<?php

namespace Feed\Service\Feed\Configuration;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\Configuration\Plugin\Collection;
use Feed\Service\Feed\Configuration\Plugin\ConfigurationPluginInterface;
use Zend\Stdlib\ParametersInterface;
use Exception;

/**
 * Лента постов / Сервис сборки конфигурации
 *
 * Class ConfigurationBuilder
 * @package Feed\Service\Feed
 */
class Service
{
    const LIMIT_DEFAULT = 10;

    /**
     * @var Collection
     */
    private $plugins;

    /**
     * Constructor
     *
     * @param Collection $plugins
     */
    public function __construct(Collection $plugins)
    {
        $this->plugins = $plugins;
    }

    /**
     * Допуступные параметры запроса
     *
     * @return array
     */
    public function getValidQueryParams()
    {
        return [
            ConfigurationPluginInterface::PARAM_CITY_ID,
            ConfigurationPluginInterface::PARAM_USER_ID,
            ConfigurationPluginInterface::PARAM_TYPE,
            ConfigurationPluginInterface::PARAM_PRODUCT,
            ConfigurationPluginInterface::PARAM_NICHE,
            ConfigurationPluginInterface::PARAM_MONEY_MIN,
            ConfigurationPluginInterface::PARAM_MONEY_MAX,
            ConfigurationPluginInterface::PARAM_OFFSET,
            ConfigurationPluginInterface::PARAM_ORDER_BY,
            ConfigurationPluginInterface::PARAM_MAX_ID,
        ];
    }

    /**
     * Валидация параметров из входящего запроса
     *
     * @param ParametersInterface $queryParams
     * @param boolean             $throwException
     *
     * @return boolean
     * @throws Exception
     */
    public function validateQueryParams(ParametersInterface $queryParams, $throwException = true)
    {
        $params = array_keys($queryParams->toArray());
        foreach ($params as $param) {
            if (!in_array($param, $this->getValidQueryParams(), true)) {
                if ($throwException) {
                    throw new Exception(sprintf('Передан недопустимый параметр `%s`', $param));
                }

                return false;
            }
        }

        return true;
    }

    /**
     * Получить объект конфигурации из входящего запроса
     *
     * @param ParametersInterface $queryParams
     * @return Configuration
     */
    public function getConfiguration(ParametersInterface $queryParams)
    {
        $configuration = new Configuration();
        foreach ($this->plugins as $plugin) {
            /** @var ConfigurationPluginInterface $plugin */
            $plugin->setQuery($queryParams);

            if ($plugin->shouldStart()) {
                $configuration = $plugin->apply($configuration);
            }
        }
        if (!$configuration->getLimit()) {
            $configuration->setLimit(self::LIMIT_DEFAULT);
        }

        return $configuration;
    }
}