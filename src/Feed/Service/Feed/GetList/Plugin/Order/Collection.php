<?php

namespace Feed\Service\Feed\GetList\Plugin\Order;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Лента постов / Коллекция плагинов сортировок
 *
 * Class Collection
 * @package Application\Service\Feed\Order
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить плагин
     *
     * @param FeedOrderInterface $plugin
     * @return $this
     * @throws Exception
     */
    public function attach(FeedOrderInterface $plugin)
    {
        if (!$this->getByType($plugin->getType(), false)) {
            $this->collection[] = $plugin;
        } else {
            throw new Exception('Обработчик уже был добавлен');
        }

        return $this;
    }

    /**
     * Получить обработчик по типу
     *
     * @param $type
     * @param bool $throwException
     *
     * @return FeedOrderInterface|null
     * @throws Exception
     */
    public function getByType($type, $throwException = true)
    {
        foreach ($this->collection as $plugin) {
            /** @var FeedOrderInterface $plugin */
            if ($plugin->getType() === $type) {
                return $plugin;
            }
        }
        if ($throwException) {
            throw new Exception('Обработчик не найден');
        }

        return null;
    }

    /**
     * Получить все типы объектов в коллекции
     *
     * @return array
     */
    public function getTypes()
    {
        $types = [];
        foreach ($this->collection as $plugin) {
            /** @var FeedOrderInterface $plugin */
            $types[] = $plugin->getType();
        }

        return $types;
    }
}