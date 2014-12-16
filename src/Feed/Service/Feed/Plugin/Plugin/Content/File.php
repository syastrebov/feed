<?php

namespace Feed\Service\Feed\Plugin\Plugin\Content;

use Feed\Entity\Feed as FeedEntity;
use Feed\Entity\FeedFile;
use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Feed\Service\Feed\Plugin\Adapter\FileInterface;

/**
 * Лента постов / Плагин подключения файлов к посту
 *
 * Class File
 * @package Feed\Service\Feed\Plugin\Plugin\Content
 */
final class File extends AbstractPlugin
{
    /**
     * @var FileInterface
     */
    private $adapter;

    /**
     * Constructor
     *
     * @param FileInterface $adapter
     */
    public function __construct(FileInterface $adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return FeedContentInterface::TYPE_FILE;
    }

    /**
     * Применить плагин к коллекции
     *
     * @param EntityCollection $collection
     * @return EntityCollection
     */
    public function apply(EntityCollection $collection)
    {
        foreach ($collection as $entity) {
            $this->applyEntity($entity);
        }

        return $collection;
    }

    /**
     * Применить плагин к отдельному объекту
     *
     * @param FeedEntity $entity
     * @return FeedEntity
     */
    public function applyEntity(FeedEntity $entity)
    {
        $feedFilesCollection = $this->adapter->getFilesByFeedIds([$entity->getId()]);
        if ($feedFilesCollection->count() > 0) {
            foreach ($feedFilesCollection as $feedFileEntity) {
                /** @var FeedFile $feedFileEntity */
                if ($entity->getId() === $feedFileEntity->getFeedId()) {
                    $entity->addFile($feedFileEntity);
                }
            }
        }

        return $entity;
    }
}