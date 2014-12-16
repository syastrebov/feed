<?php

namespace Feed\Service\Feed\GetList\Adapter\Doctrine\Filter;

use Feed\Service\Feed\GetList\Adapter\AdapterInterface;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Фильтр по сибирякам
 *
 * Class Siberia
 * @package Application\Service\Feed\Connector\Doctrine\Filter
 */
final class Siberia implements AdapterInterface
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
        return $select->where('m.isSiberia = 0');
    }
} 