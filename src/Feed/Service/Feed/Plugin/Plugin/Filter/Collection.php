<?php

namespace Feed\Service\Feed\Plugin\Plugin\Filter;

use Feed\Service\Feed\Plugin\Plugin\AbstractPluginCollection;
use Exception;

/**
 * Лента постов / Коллекция фильтров ленты
 *
 * Class Collection
 * @package Feed\Service\FeedPlugin\Plugin\Content
 */
class Collection extends AbstractPluginCollection
{
    /**
     * Добавить плагин в коллекцию
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
}
