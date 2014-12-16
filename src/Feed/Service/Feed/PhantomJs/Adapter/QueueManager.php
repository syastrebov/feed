<?php

namespace Feed\Service\Feed\PhantomJs\Adapter;

use BmQueue\Interfaces\QueueManagerInterface;
use Feed\Service\Feed\PhantomJs\Event\PhantomJsEventInterface;

/**
 * Лента постов / Интерфейс отправки события геренации html в очередь rabbitMQ
 *
 * Class RabbitMQ
 * @package Feed\Service\Feed\PhantomJs\Adapter
 */
class QueueManager implements AdapterInterface
{
    /**
     * @var \BmQueue\Interfaces\QueueManagerInterface
     */
    private $queueManager;

    /**
     * Constructor
     *
     * @param QueueManagerInterface $queueManager
     */
    public function __construct(QueueManagerInterface $queueManager)
    {
        $this->queueManager = $queueManager;
    }

    /**
     * {@inheritdoc}
     */
    public function send(PhantomJsEventInterface $event)
    {
        $this->queueManager->getExchange()->push(QueueManagerInterface::PHANTOM_JS_QUEUE, [
            'url' => $event->getAbsoluteUrl(),
        ]);
    }
}
