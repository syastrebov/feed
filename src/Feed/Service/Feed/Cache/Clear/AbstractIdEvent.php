<?php

namespace Feed\Service\Feed\Cache\Clear;

use Zend\EventManager\Event;

/**
 * Лента постов / Базовое событие для сброса кеша (для одного id)
 *
 * Class AbstractIdEvent
 * @package Feed\Service\Feed\Cache\Clear
 */
abstract class AbstractIdEvent extends Event implements FeedClearCacheEventInterface
{
    /**
     * @var int
     */
    protected $id;

    /**
     * Constructor
     *
     * @param int $id
     */
    public function __construct($id)
    {
        $this->id = $id;
    }
} 