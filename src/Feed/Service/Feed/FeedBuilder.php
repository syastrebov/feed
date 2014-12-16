<?php

namespace Feed\Service\Feed;

use Feed\Exception\NotFoundException;
use Feed\Service\Feed\Configuration\Service as ConfigurationBuilder;
use Feed\Service\Feed\GetList\Entity\Collection;
use Feed\Service\Feed\GetList\Service as GetListService;
use Feed\Service\Feed\Plugin\Service as FeedPluginService;
use Feed\Entity\Feed as FeedEntity;
use Feed\Repository\Feed as FeedRepository;
use Member\Entity\Member as MemberEntity;
use Zend\Stdlib\ParametersInterface;

/**
 * Лента постов / Сервис сборки ленты
 *
 * Class FeedBuilder
 * @package Feed\Service
 */
class FeedBuilder
{
    /**
     * @var ConfigurationBuilder
     */
    private $configBuilder;

    /**
     * @var GetListService
     */
    private $feedService;

    /**
     * @var FeedPluginService
     */
    private $pluginService;

    /**
     * @var FeedRepository
     */
    private $feedRepository;

    /**
     * Constructor
     *
     * @param ConfigurationBuilder $configBuilder
     * @param GetListService       $feedService
     * @param FeedPluginService    $pluginService
     * @param FeedRepository       $feedRepository
     */
    public function __construct(
        ConfigurationBuilder $configBuilder,
        GetListService       $feedService,
        FeedPluginService    $pluginService,
        FeedRepository       $feedRepository
    ) {
        $this->configBuilder  = $configBuilder;
        $this->feedService    = $feedService;
        $this->pluginService  = $pluginService;
        $this->feedRepository = $feedRepository;
    }

    /**
     * Получить запись по id
     *
     * @param integer $id
     * @return array
     * @throws \Feed\Exception\NotFoundException
     */
    public function get($id)
    {
        /** @var FeedEntity $feed */
        $feed = $this->feedRepository->findOneBy(['id' => $id]);
        if (!$feed) {
            throw new NotFoundException();
        }

        $feed = $this->pluginService->applyEntity($feed);
        return $feed->toArray(MemberEntity::FILTER_PROFILE);
    }

    /**
     * Получить список записей
     *
     * @param ParametersInterface $queryParams
     * @return array
     */
    public function getList(ParametersInterface $queryParams)
    {
        $this->configBuilder->validateQueryParams($queryParams);
        $configuration = $this->configBuilder->getConfiguration($queryParams);

        $mergedCollection = new Collection();
        $excludeNextFetch = [];

        do {
            // Получаем коллекцию из базы
            $result = $this->feedService->getList($configuration);
            // Делаем смещение для возможной следующей выборки
            $configuration->setOffset($configuration->getOffset() + $result->getCollection()->count());
            // Применяем плагины для коллекции
            $resultCollection = $this->pluginService->apply($result->getCollection());

            // Вставляем обработанную коллекцию в результирующую коллекцию
            foreach ($resultCollection as $entity) {
                /** @var FeedEntity $entity */
                if ($mergedCollection->count() < $configuration->getLimit()) {
                    $mergedCollection->attach($entity);
                } else {
                    $excludeNextFetch[] = $entity->getId();
                }
            }

        } while ($result->hasMore() && $mergedCollection->count() < $configuration->getLimit());

        return [
            'collection'  => $mergedCollection->toArrayEntities(),
            'offset'      => $result->getOffset() - count($excludeNextFetch),
            'hasMore'     => $result->hasMore(),
            'offsetLimit' => $configuration->getMaxId(),
        ];
    }
}
