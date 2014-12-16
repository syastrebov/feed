<?php

namespace Feed\Service\Feed\PhantomJs;

use Feed\Service\Feed\PhantomJs\Adapter\QueueManager;
use Feed\Service\Feed\PhantomJs\Url\AbsoluteUrl;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\View\Helper\ServerUrl;

/**
 * Лента постов / Фабрика сборки слушателя событий для генерации html версии постов через PhantomJS
 *
 * Class Factory
 * @package Feed\Service\Feed\PhantomJs
 */
class Factory implements FactoryInterface
{
    /**
     * Создаем сервис
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Listener
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \BmQueue\Service\QueueManager $queueManager */
        $queueManager = $serviceLocator->get('QueueManager');
        /** @var ServerUrl $serverUrl */
        $serverUrl = $serviceLocator->get('ViewHelperManager')->get('ServerUrl');
        /** @var \Zend\Mvc\Router\RouteStackInterface $router */
        $router    = $serviceLocator->get('Router');

        $adapter   = new QueueManager($queueManager);
        $urlHelper = new AbsoluteUrl($serverUrl, $router);

        return new Listener($adapter, $urlHelper);
    }
}
