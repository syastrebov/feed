<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Zend\Stdlib\ParametersInterface;

/**
 * Лента постов / Базовый плагин сборки конфигурации
 *
 * Class AbstractPlugin
 * @package Feed\Service\Feed\Plugin\Configuration
 */
abstract class AbstractPlugin implements ConfigurationPluginInterface
{
    /**
     * @var ParametersInterface
     */
    protected $query;

    /**
     * Задать конфигурацию
     *
     * @param ParametersInterface $query
     * @return $this
     */
    public function setQuery(ParametersInterface $query)
    {
        $this->query = $query;
        return $this;
    }

    /**
     * Получить id города из запроса
     *
     * @return int
     */
    protected function getCityIdFromQuery()
    {
        return (int)$this->query->get(ConfigurationPluginInterface::PARAM_CITY_ID);
    }

    /**
     * Получить id пользователя из запроса
     *
     * @return int
     */
    protected function getMemberIdFromQuery()
    {
        return (int)$this->query->get(ConfigurationPluginInterface::PARAM_USER_ID);
    }

    /**
     * Получить тип вкладки из запроса
     *
     * @return int
     */
    protected function getTabTypeFromQuery()
    {
        return (int)$this->query->get(ConfigurationPluginInterface::PARAM_TYPE);
    }

    /**
     * Получить тег курса из запроса
     *
     * @return int
     */
    protected function getTagProductFromQuery()
    {
        return (int)$this->query->get(ConfigurationPluginInterface::PARAM_PRODUCT);
    }

    /**
     * Получить тег нишы (интереса) из запроса
     *
     * @return int
     */
    protected function getTagNicheFromQuery()
    {
        return (int)$this->query->get(ConfigurationPluginInterface::PARAM_NICHE);
    }

    /**
     * Получить минимальную границу дохода
     *
     * @return int
     */
    protected function getMoneyMinFromQuery()
    {
        return (int)$this->query->get(ConfigurationPluginInterface::PARAM_MONEY_MIN);
    }

    /**
     * Получить максимальную границу дохода
     *
     * @return int
     */
    protected function getMoneyMaxFromQuery()
    {
        return (int)$this->query->get(ConfigurationPluginInterface::PARAM_MONEY_MAX);
    }

    /**
     * Получить смещение из запроса
     *
     * @return int
     */
    protected function getOffsetFromQuery()
    {
        return (int)$this->query->get(ConfigurationPluginInterface::PARAM_OFFSET);
    }

    /**
     * Получить тип вкладки из запроса
     *
     * @return int
     */
    protected function getSortByFromQuery()
    {
        return $this->query->get(ConfigurationPluginInterface::PARAM_ORDER_BY);
    }

    /**
     * Ограничитель для правильного постраничного вывода
     *
     * @return int
     */
    protected function getMaxIdFromQuery()
    {
        return (int)$this->query->get(ConfigurationPluginInterface::PARAM_MAX_ID);
    }
}
