<?php

namespace Feed\Service\Feed\Plugin\Adapter;

use Feed\Service\Feed\Plugin\Entity\FeedFileCollection;

/**
 * Лента постов / Интерфейс подключения файлов к посту
 *
 * Interface FileInterface
 * @package Feed\Service\Feed\Plugin\Adapter
 */
interface FileInterface
{
    /**
     * Получить файлы по id постов
     *
     * @param array $ids
     * @return FeedFileCollection
     */
    public function getFilesByFeedIds(array $ids);
}