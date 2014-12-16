<?php

namespace Feed\Service\Feed\PhantomJs\Url;

use Zend\Mvc\Router\RouteStackInterface;
use Zend\View\Helper\ServerUrl;

/**
 * Лента постов / Получить абсолютный путь по ссылке
 *
 * Class AbsoluteUrl
 * @package Feed\Service\Feed\PhantomJs
 */
class AbsoluteUrl implements UrlHelperInterface
{
    /**
     * @var \Zend\View\Helper\ServerUrl
     */
    private $serverUrl;

    /**
     * @var \Zend\Mvc\Router\RouteStackInterface
     */
    private $router;

    /**
     * Constructor
     *
     * @param ServerUrl           $serverUrl
     * @param RouteStackInterface $router
     */
    public function __construct(ServerUrl $serverUrl, RouteStackInterface $router)
    {
        $this->serverUrl = $serverUrl;
        $this->router    = $router;
    }

    /**
     * {@inheritdoc}
     */
    public function getUrl($route, array $params)
    {
        return $this->serverUrl->__invoke() . $this->router->assemble($params, ['name' => $route]);
    }
}
