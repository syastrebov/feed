<?php

namespace Feed\Service\Feed\GetList\Adapter;

use Feed\Service\Feed\GetList\Entity\Collection;

/**
 * Лента постов / Интерфейс получения списка
 *
 * Interface SelectInterface
 * @package Application\Service\Feed\Connector
 */
interface SelectInterface
{
    /**
     * Получить новый запрос
     *
     * @return $this
     */
    public function init();

    /**
     * Подключить теги
     *
     * @param string $alias
     * @return $this
     */
    public function joinTags($alias);

    /**
     * Подключить профиль
     *
     * @return $this
     */
    public function joinProfile();

    /**
     * Добавить условие выборки
     *
     * @param mixed $criteria
     * @return $this
     */
    public function where($criteria);

    /**
     * Добавить условие сортировки
     *
     * @param array $order
     * @return $this
     */
    public function order(array $order);

    /**
     * Задать ограничение по количеству записей
     *
     * @param int $limit
     * @return $this
     */
    public function limit($limit);

    /**
     * Задать смещение
     *
     * @param int $offset
     * @return $this
     */
    public function offset($offset);

    /**
     * Вернуть коллекцию объектов
     *
     * @return Collection
     */
    public function getCollection();
}
