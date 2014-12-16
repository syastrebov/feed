<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Service\Feed\Plugin\Plugin\AbstractPluginCollection;
use Exception;

/**
 * Лента постов / Коллекция плагинов заполнения контента постов
 *
 * Class Collection
 * @package Feed\Service\FeedPlugin\Plugin\Content
 */
class Collection extends AbstractPluginCollection
{
    /**
     * Добавить плагин в коллекцию
     *
     * @param FeedContentInterface $plugin
     * @return $this
     * @throws Exception
     */
    public function attach(FeedContentInterface $plugin)
    {
        if (!$this->getByType($plugin->getType(), false)) {
            $this->collection[] = $plugin;
        } else {
            throw new Exception('Обработчик уже был добавлен');
        }

        return $this;
    }
}
