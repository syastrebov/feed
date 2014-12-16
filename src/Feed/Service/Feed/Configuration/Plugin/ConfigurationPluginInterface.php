<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Zend\Stdlib\ParametersInterface;

/**
 * Лента постов / Интерфейс плагина для сборки конфигурации
 *
 * Interface ConfigurationPluginInterface
 * @package Feed\Service\Feed\Plugin
 */
interface ConfigurationPluginInterface
{
    const TYPE_TAB_TYPE                   = 1;
    const TYPE_CITY                       = 2;
    const TYPE_MONEY                      = 3;
    const TYPE_OFFSET                     = 4;
    const TYPE_USEFUL                     = 5;
    const TYPE_OWNER                      = 6;
    const TYPE_TAG_PRODUCT                = 7;
    const TYPE_TAG_NICHE                  = 8;
    const TYPE_ADMIN                      = 9;
    const TYPE_LIMIT_BY_MAX_INTERVAL_DATE = 10;
    const TYPE_LIMIT_BY_FIRST_PAGE_DATE   = 11;

    const PARAM_CITY_ID    = 'city_id';
    const PARAM_USER_ID    = 'user_id';
    const PARAM_TYPE       = 'type';
    const PARAM_PRODUCT    = 'product';
    const PARAM_NICHE      = 'niche';
    const PARAM_MONEY_MIN  = 'money_min';
    const PARAM_MONEY_MAX  = 'money_max';
    const PARAM_OFFSET     = 'offset';
    const PARAM_ORDER_BY   = 'orderBy';
    const PARAM_MAX_ID     = 'offsetLimit';

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType();

    /**
     * Задать конфигурацию
     *
     * @param ParametersInterface $query
     * @return $this
     */
    public function setQuery(ParametersInterface $query);

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart();

    /**
     * Применить плагин
     *
     * @param Configuration $config
     * @return Configuration
     */
    public function apply(Configuration $config);
}