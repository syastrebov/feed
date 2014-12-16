<?php

namespace Feed\Service\Feed\Plugin\Adapter\Doctrine;

use Feed\Service\Feed\Plugin\Entity\MemberCollection;
use Feed\Service\Feed\Plugin\Adapter\UserInterface;

/**
 * Лента постов / Получения модели пользователя
 *
 * Class User
 * @package Feed\Service\FeedPlugin\Plugin\Adapter\Doctrine
 */
final class User extends AbstractAdapter implements UserInterface
{
    /**
     * Получить пользователей по id
     *
     * @param array $ids
     * @return MemberCollection
     */
    public function getMembersByIds(array $ids)
    {
        $result = $this->entityManager->getRepository('Member\Entity\Member')->findBy(['id' => $ids]);
        $collection = new MemberCollection();
        foreach ($result as $member) {
            $collection->attach($member);
        }

        return $collection;
    }
}
