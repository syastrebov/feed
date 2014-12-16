<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Entity\Feed as FeedEntity;
use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Member\Repository\Member as MemberRepository;
use Member\Entity\Member as MemberEntity;

/**
 * Лента постов / Плагин подключения информации о подписке
 *
 * Class UserSubscribe
 * @package Feed\Service\FeedPlugin\Plugin\Content
 */
final class UserSubscribe extends AbstractPlugin
{
    /**
     * @var \Member\Entity\Member
     */
    private $identity;

    /**
     * @var \Member\Repository\Member
     */
    private $memberRepository;

    /**
     * Constructor
     *
     * @param MemberEntity     $identity
     * @param MemberRepository $memberRepository
     */
    public function __construct(MemberEntity $identity, MemberRepository $memberRepository)
    {
        $this->identity         = $identity;
        $this->memberRepository = $memberRepository;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedContentInterface::TYPE_USER_SUBSCRIBE;
    }

    /**
     * Применить плагин к коллекции
     *
     * @param EntityCollection $collection
     * @return EntityCollection
     */
    public function apply(EntityCollection $collection)
    {
        $userIds = $this->getUserIds($collection);
        if (!empty($userIds)) {
            $members = $this->getMembers($collection);
            foreach ($members as $member) {
                /** @var \Member\Entity\Member $member */
                $this->memberRepository->setMoreInfo($member, $this->identity);
            }
        }

        return $collection;
    }

    /**
     * Применить плагин к отдельному объекту
     *
     * @param FeedEntity $entity
     * @return mixed
     */
    public function applyEntity(FeedEntity $entity)
    {
        $this->memberRepository->setMoreInfo($entity->getMember(), $this->identity);
        return $entity;
    }
}
