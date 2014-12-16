<?php

namespace Feed\Service\Feed\GetList\Adapter\Doctrine\Filter;

use Feed\Service\Feed\GetList\Adapter\AdapterInterface;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Doctrine\ORM\Query\Expr;

/**
 * Лента постов / Фильтр по тегам выбора нишы
 *
 * Class Tag
 * @package Application\Service\Feed\Adapter\Doctrine\Filter
 */
final class TagNiche implements AdapterInterface
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
        return $select->joinTags('ft2')->where($expr->in('ft2.tagId', $config->getNicheTags()));
    }
} 