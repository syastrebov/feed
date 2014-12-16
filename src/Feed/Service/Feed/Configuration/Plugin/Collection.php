<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Лента постов / Коллекция плагинов для сборки конфигурации
 *
 * Class Collection
 * @package Feed\Service\Feed\Plugin\Configuration
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить плагин
     *
     * @param ConfigurationPluginInterface $plugin
     * @return $this
     * @throws Exception
     */
    public function attach(ConfigurationPluginInterface $plugin)
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
     * @return ConfigurationPluginInterface|null
     * @throws Exception
     */
    public function getByType($type, $throwException = true)
    {
        foreach ($this->collection as $plugin) {
            /** @var ConfigurationPluginInterface $plugin */
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
            /** @var ConfigurationPluginInterface $plugin */
            $types[] = $plugin->getType();
        }

        return $types;
    }
}