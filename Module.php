<?php

namespace Feed;

use Application\Repository\Tag as TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Cache\Cache as CacheInterface;
use Feed\Repository\Feed as FeedRepository;
use Feed\Service\Feed\FeedBuilder;
use Feed\Service\Feed\Cache\Clear\Listener as FeedClearCacheListener;
use Feed\View\Helper\ProfileList;
use Feed\View\Helper\TagFilter;
use Feed\Service\Feed\Configuration\Service as ConfigurationBuilder;
use Feed\Service\Feed\GetList\Service as FeedService;
use Feed\Service\Feed\Plugin\Service as FeedPluginService;
use Zend\ServiceManager\ServiceManager;
use Zend\View\HelperPluginManager;

/**
 * Модуль ленты постов
 *
 * Class Module
 * @package Feed
 */
class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        if (class_exists('\ClassLoader')) {
            \ClassLoader::getInstance()->registerNamespace(__NAMESPACE__, __DIR__ . '/src');
            return [];
        }

        return [
            'Zend\Loader\StandardAutoloader' => [
                'namespaces' => [
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ],
            ],
        ];
    }

    public function getServiceConfig()
    {
        return [
            'factories' => [
                'feedBuilderService' => function (ServiceManager $sm) {
                    /** @var ConfigurationBuilder $configBuilder */
                    $configBuilder = $sm->get('FeedConfigurationBuilder');
                    /** @var FeedService $feedService */
                    $feedService = $sm->get('FeedService');
                    /** @var FeedPluginService $feedPluginService */
                    $feedPluginService = $sm->get('FeedPluginService');
                    /** @var \Doctrine\ORM\EntityManager $entityManager */
                    $entityManager = $sm->get('Doctrine\ORM\EntityManager');
                    /** @var FeedRepository $feedRepository */
                    $feedRepository = $entityManager->getRepository('Feed\Entity\Feed');

                    return new FeedBuilder($configBuilder, $feedService, $feedPluginService, $feedRepository);
                },
                'feedClearCacheListener' => function(ServiceManager $sm) {
                    /** @var CacheInterface $cache */
                    $cache = $sm->get('doctrine.cache.zend.static.local');
                    return new FeedClearCacheListener($cache);
                },
            ],
        ];
    }

    public function getViewHelperConfig()
    {
        return [
            'factories' => [
                'feedTagFilter' => function (HelperPluginManager $pm) {
                    $sm = $pm->getServiceLocator();
                    /** @var EntityManagerInterface $entityManager */
                    $entityManager = $sm->get('EntityManager');
                    /** @var TagRepository $tagRepository */
                    $tagRepository = $entityManager->getRepository('Application\Entity\Tag');

                    return new TagFilter($tagRepository);
                },
                'feedProfileList' => function (HelperPluginManager $pm) {
                    $sm = $pm->getServiceLocator();
                    $feedBuilder = $sm->get('feedBuilderService');

                    return new ProfileList($feedBuilder);
                },
            ],
        ];
    }
}
