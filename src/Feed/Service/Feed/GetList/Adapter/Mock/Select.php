<?php

namespace Feed\Service\Feed\GetList\Adapter\Mock;

use Feed\Service\Feed\GetList\Adapter\SelectInterface;
use Feed\Service\Feed\GetList\Entity\Collection;

/**
 * Лента постов / Заглушка для объекта выборки
 *
 * Class Select
 * @package Application\Service\Feed\Connector\Mock
 */
class Select implements SelectInterface
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
     * Получить новый запрос
     *
     * @return $this
     */
    public function init()
    {
        return $this;
    }

    /**
     * Подключить теги
     *
     * @param string $alias
     * @return $this
     */
    public function joinTags($alias)
    {
        return $this;
    }

    /**
     * Подключить профиль
     *
     * @return $this
     */
    public function joinProfile()
    {
        return $this;
    }

    /**
     * Добавить условие выборки
     *
     * @param mixed $criteria
     * @return $this
     */
    public function where($criteria)
    {
        return $this;
    }

    /**
     * Добавить условие сортировки
     *
     * @param array $order
     * @return $this
     */
    public function order(array $order)
    {
        return $this;
    }

    /**
     * Задать ограничение по количеству записей
     *
     * @param int $limit
     * @return $this
     */
    public function limit($limit)
    {
        return $this;
    }

    /**
     * Задать смещение
     *
     * @param int $offset
     * @return $this
     */
    public function offset($offset)
    {
        return $this;
    }

    /**
     * Вернуть коллекцию объектов
     *
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }
}