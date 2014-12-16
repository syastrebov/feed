<?php

namespace Feed\Service\Feed\GetList\Adapter\Doctrine\Order;

use Feed\Service\Feed\GetList\Adapter\AdapterInterface;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Сортировка по дате
 *
 * Class Date
 * @package Application\Service\Feed\Adapter\Doctrine\Order
 */
final class Date implements AdapterInterface
{
    /**
     * Применить плагин
     *
     * @param SelectInterface $select
     * @param Configuration   $config
     *
     * @return SelectInterface
     */
    public function apply(SelectInterface $select, Configuration $config)
    {
        return $select->order(['f.id' => 'DESC']);
    }
}