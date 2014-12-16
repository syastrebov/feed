<?php

namespace Feed\Service\Feed\Plugin\Adapter\Mock;

use Feed\Service\Feed\Plugin\Entity\MemberCollection;
use Feed\Service\Feed\Plugin\Adapter\UserInterface;
use Member\Entity\Member;

/**
 * Лента постов / Заглушка получения модели пользователя
 *
 * Class User
 * @package Feed\Service\FeedPlugin\Plugin\Adapter\Mock
 */
class User implements UserInterface
{
    /**
     * @var \Member\Entity\Member
     */
    private $customMember;

    /**
     * Constructor
     *
     * @param Member $customMember
     */
    public function __construct(Member $customMember = null)
    {
        $this->customMember = $customMember;
    }

    /**
     * Получить пользователей по id
     *
     * @param array $ids
     * @return MemberCollection
     */
    public function getMembersByIds(array $ids)
    {
        $collection = new MemberCollection();
        if ($this->customMember) {
            $collection->attach($this->customMember);
        }

        return $collection;
    }
}
