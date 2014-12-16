<?php

namespace Feed\Service\Feed\GetList\Plugin\Filter;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Лента постов / Коллекция плагинов фильтров
 *
 * Class Collection
 * @package Application\Service\Feed\Filter
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить плагин
     *
     * @param FeedFilterInterface $plugin
     * @return $this
     * @throws Exception
     */
    public function attach(FeedFilterInterface $plugin)
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
     * @return FeedFilterInterface|null
     * @throws Exception
     */
    public function getByType($type, $throwException = true)
    {
        foreach ($this->collection as $plugin) {
            /** @var FeedFilterInterface $plugin */
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
            /** @var FeedFilterInterface $plugin */
            $types[] = $plugin->getType();
        }

        return $types;
    }
}