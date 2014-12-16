<?php

namespace Feed\Service\Feed\GetList\Entity;

use Member\Entity\Member;
use Feed\Entity\Feed;
use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Лента постов / Коллекция выборки
 *
 * Class Collection
 * @package Application\Service\Feed\Entity
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить пост
     *
     * @param Feed $entity
     * @return $this
     * @throws Exception
     */
    public function attach(Feed $entity)
    {
        if (!$this->getById($entity->getId(), false)) {
            $this->collection[] = $entity;
        } else {
            throw new Exception('Объект уже был добавлен');
        }

        return $this;
    }

    /**
     * Удалить пост
     *
     * @param int  $id
     * @param bool $throwException
     *
     * @return $this
     * @throws Exception
     */
    public function detach($id, $throwException = true)
    {
        $found = false;
        foreach ($this->collection as $num => $entity) {
            /** @var Feed $entity */
            if ($id === $entity->getId()) {
                unset($this->collection[$num]);
                $found = true;
            }
        }
        if (!$found && $throwException) {
            throw new Exception('Объект не найден');
        }

        return $this;
    }

    /**
     * Получить пост по id
     *
     * @param int $id
     * @param bool $throwException
     *
     * @return Feed|null
     * @throws Exception
     */
    public function getById($id, $throwException = true)
    {
        foreach ($this->collection as $entity) {
            /** @var Feed $entity */
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
        if ($throwException) {
            throw new Exception('Объект не найден');
        }

        return null;
    }

    /**
     * Преобразовать коллекцию в массив
     *
     * @return array
     */
    public function toArrayEntities()
    {
        $collection = [];
        foreach ($this->collection as $entity) {
            /** @var Feed $entity */
            $collection[] = $entity->toArray(Member::FILTER_PROFILE);
        }

        return $collection;
    }
}