<?php

namespace Feed\Service\Feed\PhantomJs\Adapter;
use Feed\Service\Feed\PhantomJs\Event\PhantomJsEventInterface;

/**
 * Лента постов / Интерфейс отправки события геренации html
 *
 * Interface AdapterInterface
 * @package Feed\Service\Feed\PhantomJs\Adapter
 */
interface AdapterInterface
{
    /**
     * Отправить событие
     *
     * @param PhantomJsEventInterface $event
     */
    public function send(PhantomJsEventInterface $event);
}
