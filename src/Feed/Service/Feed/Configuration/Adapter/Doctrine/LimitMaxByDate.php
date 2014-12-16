<?php

namespace Feed\Service\Feed\Configuration\Adapter\Doctrine;

/**
 * Лента постов / Получение максимального ограничения для вывода ленты
 *
 * Class LimitByFirstPageDate
 * @package Feed\Service\Feed\Adapter\Doctrine\Configuration
 */
final class LimitMaxByDate extends AbstractLimitByDate
{
    /**
     * Получить id
     *
     * @return int
     */
    public function getId()
    {
        return $this->feedRepository->getMaxIdByDate($this->dateTime);
    }
}