<?php

namespace Feed\Service\Feed\GetList\Adapter\Doctrine;

use Feed\Entity\Feed;
use Feed\Service\Feed\GetList\Adapter\InsertInterface;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Лента постов / Добавление в базу черед Doctrine ORM
 *
 * Class Insert
 * @package Application\Service\Feed\Adapter\Doctrine
 */
class Insert implements InsertInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Добавить элемент в базу
     *
     * @param Feed $entity
     * @return $this
     */
    public function insert(Feed $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();

        return $this;
    }
}
