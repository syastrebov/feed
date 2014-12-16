<?php

namespace Feed\Service\Feed\GetList\Adapter\Doctrine\Filter;

use Feed\Service\Feed\GetList\Adapter\AdapterInterface;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Doctrine\ORM\Query\Expr;

/**
 * Лента постов / Фильтр ограничения вывода ленты для правильной пагинации (с учетом добавления новых постов)
 *
 * Class LimitByFirstPageDate
 * @package Feed\Service\Feed\Adapter\Doctrine\Filter
 */
final class LimitByFirstPageDate implements AdapterInterface
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
        return $select->where($expr->lte('f.id', $config->getMaxId()));
    }
}