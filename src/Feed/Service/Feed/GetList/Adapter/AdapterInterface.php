<?php

namespace Feed\Service\Feed\GetList\Adapter;

use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Интерфейс обработки запроса
 *
 * Interface AdapterInterface
 * @package Application\Service\Feed\Adapter
 */
interface AdapterInterface
{
    /**
     * Применить плагин
     *
     * @param SelectInterface $select
     * @param Configuration   $config
     *
     * @return SelectInterface
     */
    public function apply(SelectInterface $select, Configuration $config);
}