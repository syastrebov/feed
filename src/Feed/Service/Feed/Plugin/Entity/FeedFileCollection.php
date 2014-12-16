<?php

namespace Feed\Service\Feed\Plugin\Entity;

use BmCommon\Collection\AbstractCollection;
use Feed\Entity\FeedFile;

/**
 * Лента постов / Коллекция файлов поста загружаемые из плагина
 *
 * Class FeedFileCollection
 * @package Feed\Service\Feed\Plugin\Entity
 */
class FeedFileCollection extends AbstractCollection
{
    /**
     * Добавить модель
     *
     * @param FeedFile $entity
     * @return $this
     */
    public function attach(FeedFile $entity)
    {
        $this->collection[] = $entity;
        return $this;
    }
}