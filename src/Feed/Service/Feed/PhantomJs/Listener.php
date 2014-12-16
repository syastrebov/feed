<?php

namespace Feed\Service\Feed\PhantomJs;

use Feed\Service\Feed\PhantomJs\Adapter\AdapterInterface;
use Feed\Service\Feed\PhantomJs\Event\PhantomJsEventInterface;
use Feed\Service\Feed\PhantomJs\Url\UrlHelperAwareInterface;
use Feed\Service\Feed\PhantomJs\Url\UrlHelperInterface;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;

/**
 * Лента постов / Слушатель для генерации html версии постов через PhantomJS
 *
 * Class Listener
 * @package Feed\Service\Feed\PhantomJs
 */
class Listener extends AbstractListenerAggregate
{
    /**
     * @var AdapterInterface
     */
    private $adapter;

    /**
     * Constructor
     *
     * @param AdapterInterface   $adapter
     * @param UrlHelperInterface $urlHelper
     */
    public function __construct(AdapterInterface $adapter, UrlHelperInterface $urlHelper)
    {
        $this->adapter   = $adapter;
        $this->urlHelper = $urlHelper;
    }

    /**
     * Вешаем слушатели
     *
     * @param EventManagerInterface $events
     */
    public function attach(EventManagerInterface $events)
    {
        $events->getSharedManager()->attach('*', PhantomJsEventInterface::EVENT_TYPE, [$this, 'handler']);
    }

    /**
     * Обработчик отправки события
     *
     * @param PhantomJsEventInterface $event
     */
    public function handler(PhantomJsEventInterface $event)
    {
        if ($event instanceof UrlHelperAwareInterface) {
            $event->setUrlHelper($this->urlHelper);
        }

        $this->adapter->send($event);
    }
}
