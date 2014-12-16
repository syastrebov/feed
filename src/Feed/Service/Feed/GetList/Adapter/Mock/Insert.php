<?php

namespace Feed\Service\Feed\GetList\Adapter\Mock;

use Feed\Entity\Feed;
use Feed\Service\Feed\GetList\Adapter\InsertInterface;
use Feed\Service\Feed\GetList\Entity\Collection;

/**
 * Лента постов / Заглушка добавления элемента в базу
 *
 * Class Insert
 * @package Application\Service\Feed\Adapter\Mock
 */
class Insert implements InsertInterface
{
    /**
     * @var array
     */
    private $collection;

    /**
     * Constructor
     *
     * @param Collection $collection
     */
    public function __construct(Collection $collection)
    {
        $this->collection = $collection;
    }

    /**
     * Добавить элемент в базу
     *
     * @param Feed $entity
     * @return $this
     */
    public function insert(Feed $entity)
    {
        $this->collection->attach($entity);
        return $this;
    }
}