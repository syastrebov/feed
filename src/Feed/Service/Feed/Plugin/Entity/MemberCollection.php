<?php

namespace Feed\Service\Feed\Plugin\Entity;

use BmCommon\Collection\AbstractCollection;
use Member\Entity\Member;
use Exception;

/**
 * Лента постов / Коллекция пользователей в ленте
 *
 * Class MemberCollection
 * @package Feed\Service\FeedPlugin\Entity
 */
class MemberCollection extends AbstractCollection
{
    /**
     * Добавить модель
     *
     * @param Member $entity
     * @return $this
     * @throws Exception
     */
    public function attach(Member $entity)
    {
        if (!$this->getById($entity->getId(), false)) {
            $this->collection[] = $entity;
        } else {
            throw new Exception('Объект уже был добавлен');
        }

        return $this;
    }

    /**
     * Получить пост по id
     *
     * @param int $id
     * @param bool $throwException
     *
     * @return Member|null
     * @throws Exception
     */
    public function getById($id, $throwException = true)
    {
        foreach ($this->collection as $entity) {
            /** @var Member $entity */
            if ($entity->getId() == $id) {
                return $entity;
            }
        }
        if ($throwException) {
            throw new Exception('Объект не найден');
        }

        return null;
    }
}
