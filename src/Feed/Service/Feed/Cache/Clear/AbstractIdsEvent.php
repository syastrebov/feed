<?php

namespace Feed\Service\Feed\Cache\Clear;

use Zend\EventManager\Event;
use Exception;

/**
 * Лента постов / Базовое событие для сброса кеша (для массива id)
 *
 * Class AbstractEvent
 * @package Feed\Service\FeedCache\FeedPlugin
 */
abstract class AbstractIdsEvent extends Event implements FeedClearCacheEventInterface
{
    /**
     * @var array
     */
    protected $ids;

    /**
     * Constructor
     *
     * @param array|int $ids
     * @throws Exception
     */
    public function __construct($ids)
    {
        switch(true) {
            case is_int($ids):
                $this->ids = [$ids];
                break;
            case is_array($ids):
                $this->ids = $ids;
                break;
            default:
                throw new Exception('Неверный тип id');
        }

    }
}