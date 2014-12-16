<?php

namespace Feed\Service\Feed\Plugin\Plugin\Filter;

use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Feed\Service\Feed\Plugin\Adapter\UserHiddenPostsInterface;
use Member\Entity\Member as MemberEntity;

/**
 * Лента постов /  Фильтр я не хочу это видеть
 *
 * Class IDontWantToSeeThis
 * @package Feed\Service\FeedPlugin\Plugin
 */
final class IDontWantToSeeThis implements FeedFilterInterface
{
    /**
     * @var MemberEntity
     */
    private $identity;

    /**
     * @var UserHiddenPostsInterface
     */
    private $adapter;

    /**
     * Constructor
     *
     * @param MemberEntity             $identity
     * @param UserHiddenPostsInterface $adapter
     */
    public function __construct(MemberEntity $identity, UserHiddenPostsInterface $adapter)
    {
        $this->identity = $identity;
        $this->adapter  = $adapter;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedFilterInterface::TYPE_I_DONT_WANT_TO_SEE_THIS;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return $this->identity && $this->identity->getId() > 0;
    }

    /**
     * Применить плагин к коллекции
     *
     * @param EntityCollection $collection
     * @return EntityCollection
     */
    public function apply(EntityCollection $collection)
    {
        $hiddenIds = $this->adapter->getIds($this->identity->getId());
        foreach ($hiddenIds as $hiddenId) {
            $collection->detach($hiddenId, false);
        }

        return $collection;
    }
}
