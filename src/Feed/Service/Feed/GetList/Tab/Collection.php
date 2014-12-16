<?php

namespace Feed\Service\Feed\GetList\Tab;

use BmCommon\Collection\AbstractCollection;
use Exception;

/**
 * Лента постов / Коллекция закладок
 *
 * Class Collection
 * @package Application\Service\Feed\Tab
 */
class Collection extends AbstractCollection
{
    /**
     * Добавить плагин
     *
     * @param FeedTabInterface $plugin
     * @return $this
     * @throws Exception
     */
    public function attach(FeedTabInterface $plugin)
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
     * @return FeedTabInterface|null
     * @throws Exception
     */
    public function getByType($type, $throwException = true)
    {
        foreach ($this->collection as $plugin) {
            /** @var FeedTabInterface $plugin */
            if ($plugin->getType() === $type) {
                return $plugin;
            }
        }
        if ($throwException) {
            throw new Exception('Обработчик не найден');
        }

        return null;
    }
}