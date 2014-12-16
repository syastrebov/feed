<?php

namespace Feed\Service\Feed\GetList\Adapter\Doctrine\Filter;

use Feed\Service\Feed\GetList\Adapter\AdapterInterface;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Doctrine\ORM\Query\Expr;

/**
 * Лента постов / Фильтр по доходу
 *
 * Class Money
 * @package Application\Service\Feed\Adapter\Doctrine\Filter
 */
final class Money implements AdapterInterface
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
        if ($config->getMoneyMin()) {
            $select->joinProfile()->where($expr->gte('p.profit', $config->getMoneyMin()));
        }
        if ($config->getMoneyMax()) {
            $select->joinProfile()->where($expr->lte('p.profit', $config->getMoneyMax()));
        }

        return $select;
    }
}