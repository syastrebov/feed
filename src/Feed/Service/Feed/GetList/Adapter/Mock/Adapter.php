<?php

namespace Feed\Service\Feed\GetList\Adapter\Mock;

use Feed\Service\Feed\GetList\Adapter\AdapterInterface;
use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Adapter\Mock\Select as MockSelect;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Заглушка для адаптера
 *
 * Class Adapter
 * @package Application\Service\Feed\Adapter\Mock
 */
class Adapter implements AdapterInterface
{
    /**
     * @var MockSelect
     */
    private $customSelect;

    /**
     * Constructor
     *
     * @param MockSelect $customSelect
     */
    public function __construct(MockSelect $customSelect = null)
    {
        $this->customSelect = $customSelect;
    }

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
        $select = $this->customSelect ? : $select;
        return $select
            ->joinTags('ft')
            ->joinProfile()
            ->where([])
            ->order([])
            ->limit(1)
            ->offset(1);
    }
} 