<?php

namespace Feed\Service\Feed\PhantomJs\Event;

/**
 * Лента постов / Интерфейс события геренации html копии страницы через PhantomJs
 *
 * Interface PhantomJsEventInterface
 * @package Feed\Service\Feed\PhantomJs\Event
 */
interface PhantomJsEventInterface
{
    const EVENT_TYPE = 'phantom.js.generate';

    /**
     * Абсолютный путь до генерируемой страницы
     *
     * @return string
     */
    public function getAbsoluteUrl();
}
