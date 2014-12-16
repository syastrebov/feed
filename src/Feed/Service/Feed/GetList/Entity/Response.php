<?php

namespace Feed\Service\Feed\GetList\Entity;

/**
 * Лента постов / Ответ сервиса
 *
 * Class FeedServiceResponse
 * @package Application\Entity
 */
class Response
{
    /**
     * Полученная коллекция
     *
     * @var Collection
     */
    private $collection;

    /**
     * Новое смещение
     *
     * @var int
     */
    private $offset;

    /**
     * Отобразать кнопку показать еще
     *
     * @var bool
     */
    private $hasMore;

    /**
     * Constructor
     *
     * @param Collection $collection
     * @param int        $offset
     * @param bool       $hasMore
     */
    public function __construct(Collection $collection, $offset, $hasMore)
    {
        $this->collection = $collection;
        $this->offset     = (int)$offset;
        $this->hasMore    = (bool)$hasMore;
    }

    /**
     * Полученная коллекция
     *
     * @return Collection
     */
    public function getCollection()
    {
        return $this->collection;
    }

    /**
     * Новое смещение
     *
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }

    /**
     * Отобразать кнопку показать еще
     *
     * @return bool
     */
    public function hasMore()
    {
        return $this->hasMore;
    }
}