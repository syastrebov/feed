<?php

namespace Feed\Listener;

use Spam\Event\Spam as SpamEvent;
use Spam\Service\Spam as SpamService;

use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventManagerInterface;
use Zend\ServiceManager\ServiceManager;
use Zend\ServiceManager\ServiceManagerAwareInterface;

class Spam extends AbstractListenerAggregate implements ServiceManagerAwareInterface
{
    /** @var ServiceManager */
    protected $serviceManager;

    public function attach(EventManagerInterface $events)
    {
        $events->getSharedManager()->attach('*', SpamEvent::EVENT_MARK_SPAM, array($this, 'checkSpamPost'));
    }

    /**
     * Проверить, удалять ли публикацию, как спамерскую
     *
     * @param SpamEvent $event
     * @return bool
     */
    public function checkSpamPost(SpamEvent $event)
    {
        if ($event->getTypeId() == SpamService::TYPE_POST) {
            if ($event->getCount() >= SpamService::TYPE_POST_THRESHOLD) {
                /** @var \Feed\Repository\Feed $feedRepository */
                $feedRepository = $this->serviceManager->get('EntityManager')->getRepository('Feed\Entity\Feed');
                $feedRepository->deleteFeed($event->getTargetId());
            }
        }
        return false;
    }

    /**
     * Set service manager
     *
     * @param ServiceManager $serviceManager
     */
    public function setServiceManager(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }


}