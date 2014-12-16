<?php

namespace Feed\Service\Feed\Plugin\Entity;

use BmCommon\Collection\AbstractCollection;

/**
 * Лента постов / Коллекция тегов поста загружаемые из плагина
 *
 * Class FeedTagCollection
 * @package Feed\Service\FeedPlugin\Entity
 */
class FeedTagCollection extends AbstractCollection
{
    /**
     * Добавить модель
     *
     * @param FeedTag $entity
     * @return $this
     */
    public function attach(FeedTag $entity)
    {
        $this->collection[] = $entity;
        return $this;
    }
}