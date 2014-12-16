<?php

namespace Feed\Service\Feed\Plugin\Plugin;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Лента постов / Коллекция, хранящая плагины
 *
 * Class Collection
 * @package Feed\Service\FeedPlugin
 */
abstract class AbstractPluginCollection extends AbstractCollection
{
    /**
     * Получить обработчик по типу
     *
     * @param $type
     * @param bool $throwException
     *
     * @return FeedPluginInterface|null
     * @throws Exception
     */
    public function getByType($type, $throwException = true)
    {
        foreach ($this->collection as $plugin) {
            /** @var FeedPluginInterface $plugin */
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
            /** @var FeedPluginInterface $plugin */
            $types[] = $plugin->getType();
        }

        return $types;
    }
}
