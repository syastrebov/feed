<?php

namespace Feed\Service\Feed\Configuration\Adapter\Doctrine;

/**
 * Лента постов / Получение минимального ограничения для вывода ленты
 *
 * Class LimitByFirstPageDate
 * @package Feed\Service\Feed\Adapter\Doctrine\Configuration
 */
final class LimitMinByDate extends AbstractLimitByDate
{
    /**
     * Получить id
     *
     * @return int
     */
    public function getId()
    {
        return $this->feedRepository->getMinIdByDate($this->dateTime);
    }
}