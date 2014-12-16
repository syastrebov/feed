<?php

namespace Feed\Service\Feed\PhantomJs\Event;

use Feed\Entity\Feed as FeedEntity;
use Feed\Service\Feed\PhantomJs\Url\UrlHelperAwareInterface;
use Feed\Service\Feed\PhantomJs\Url\UrlHelperInterface;
use Zend\EventManager\Event;

/**
 * Лента постов / Событие генерации html версии поста
 *
 * Class Event
 * @package Feed\Service\Feed\PhantomJs
 */
class Post extends Event implements PhantomJsEventInterface, UrlHelperAwareInterface
{
    /**
     * @var UrlHelperInterface
     */
    private $urlHelper;

    /**
     * @var FeedEntity
     */
    private $entity;

    /**
     * Constructor
     *
     * @param FeedEntity $entity
     */
    public function __construct(FeedEntity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * {@inheritdoc}
     */
    public function getAbsoluteUrl()
    {
        return $this->urlHelper->getUrl('feed.list.post', ['id' => $this->entity->getId()]);
    }

    /**
     * {@inheritdoc}
     */
    public function setUrlHelper(UrlHelperInterface $urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }
}
