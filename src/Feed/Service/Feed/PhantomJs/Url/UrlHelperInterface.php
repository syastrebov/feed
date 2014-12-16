<?php

namespace Feed\Service\Feed\PhantomJs\Url;

/**
 * Лента постов / Получить ссылку
 *
 * Interface UrlHelperInterface
 * @package Feed\Service\Feed\PhantomJs
 */
interface UrlHelperInterface
{
    /**
     * Получить ссылку
     *
     * @param string $route
     * @param array  $params
     *
     * @return string
     */
    public function getUrl($route, array $params);
}
