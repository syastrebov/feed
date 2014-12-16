<?php

namespace Feed\Service\Feed\PhantomJs\Url;

/**
 * Лента постов / Di для сервиса получения абсолютного пути по ссылке
 *
 * Interface AbsoluteUrlHelperAwareInterface
 * @package Feed\Service\Feed\PhantomJs
 */
interface UrlHelperAwareInterface
{
    /**
     * Задать сервис для получения абсолютной ссылки
     *
     * @param UrlHelperInterface $urlHelper
     */
    public function setUrlHelper(UrlHelperInterface $urlHelper);
}
