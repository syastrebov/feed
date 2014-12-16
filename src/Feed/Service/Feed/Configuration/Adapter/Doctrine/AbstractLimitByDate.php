<?php

namespace Feed\Service\Feed\Configuration\Adapter\Doctrine;

use Feed\Service\Feed\Configuration\Adapter\ConfigurationLimitByInterface;
use Feed\Repository\Feed as FeedRepository;
use DateTime;

/**
 * Лента постов / Получение ограничения для вывода ленты
 *
 * Class AbstractLimitByDate
 * @package Feed\Service\Feed\Adapter\Doctrine\Configuration
 */
abstract class AbstractLimitByDate implements ConfigurationLimitByInterface
{
    /**
     * @var DateTime
     */
    protected $dateTime;

    /**
     * Constructor
     *
     * @param FeedRepository $feedRepository
     * @param DateTime       $dateTime
     */
    public function __construct(FeedRepository $feedRepository, DateTime $dateTime)
    {
        $this->feedRepository = $feedRepository;
        $this->dateTime       = $dateTime;
    }
}