<?php

namespace Feed\Service\Feed\Plugin\Adapter\Doctrine;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Лента постов / Базовый адаптер с кешом и entityManager'ом
 *
 * Class AbstractAdapter
 * @package Feed\Service\FeedPlugin\Plugin\Adapter\Doctrine
 */
abstract class AbstractAdapter
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * Constructor
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
}
