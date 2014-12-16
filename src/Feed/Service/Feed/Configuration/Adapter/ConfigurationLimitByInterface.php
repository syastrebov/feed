<?php

namespace Feed\Service\Feed\Configuration\Adapter;

/**
 * Лента постов / Интерфейс получения ограничения для вывода ленты
 *
 * Interface ConfigurationLimitByInterface
 * @package Feed\Service\Feed\Adapter
 */
interface ConfigurationLimitByInterface
{
    /**
     * Получить id
     *
     * @return int
     */
    public function getId();
}