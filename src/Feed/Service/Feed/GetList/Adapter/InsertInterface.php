<?php

namespace Feed\Service\Feed\GetList\Adapter;

use Feed\Entity\Feed;

/**
 * Лента постов / Интерфейс добавления элемента
 *
 * Interface InsertInterface
 * @package Application\Service\Feed\Adapter
 */
interface InsertInterface
{
    /**
     * Добавить элемент в базу
     *
     * @param Feed $entity
     * @return $this
     */
    public function insert(Feed $entity);
}
