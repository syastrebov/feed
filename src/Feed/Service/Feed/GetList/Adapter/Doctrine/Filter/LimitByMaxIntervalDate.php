<?php

namespace Feed\Service\Feed\GetList\Adapter\Doctrine\Filter;

use Feed\Service\Feed\GetList\Adapter\AdapterInterface;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Doctrine\ORM\Query\Expr;

/**
 * Лента постов / Фильтр ограничения вывода ленты по дате за максимальный период
 *
 * Class LimitByDate
 * @package Feed\Service\Feed\Adapter\Doctrine\Filter
 */
final class LimitByMaxIntervalDate implements AdapterInterface
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
        $expr = new Expr();
        return $select->where($expr->gte('f.id', $config->getMinId()));
    }
}