<?php

namespace Feed\Service\Feed\Configuration;

use Feed\Service\Feed\Configuration\Adapter\Doctrine as Adapter;
use Feed\Service\Feed\Plugin\Adapter\Doctrine as FeedPluginAdapter;
use Feed\Service\Feed\Cache\Adapter as CacheAdapter;
use Member\Entity\Member;
use Member\Service\Subscription as SubscriptionService;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use DateTime;

/**
 * Лента постов / Фабрика создания сервиса сборки конфигурации
 *
 * Class ConfigurationBuilder
 * @package Feed\Service\Feed\Factory
 */
class Factory implements FactoryInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * Создаем сервис
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Service
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $collection = new Plugin\Collection();
        $collection
            ->attach($this->getTabTypePlugin())
            ->attach($this->getCityPlugin())
            ->attach($this->getMoneyPlugin())
            ->attach($this->getOwnerPlugin())
            ->attach($this->getTagProductPlugin())
            ->attach($this->getTagNicheProductPlugin())
            ->attach($this->getOffsetPlugin())
            ->attach($this->getUsefulPlugin())
            ->attach($this->getIsAdminPlugin())
            ->attach($this->getLimitByFirstPageDatePlugin())
            ->attach($this->getLimitByMaxIntervalDatePlugin());

        return new Service($collection);
    }

    /**
     * Ссылка на объект сессии пользователя
     *
     * @return Member
     */
    private function getIdentity()
    {
        return $this->getSecurityService()->getIdentity();
    }

    /**
     * Ссылка на сервис безопасности
     *
     * @return \Member\Security\Service
     */
    private function getSecurityService()
    {
        return $this->serviceLocator->get('UserService')->getSecurityService();
    }

    /**
     * Doctrine менеджер моделей
     *
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->serviceLocator->get('Doctrine\ORM\EntityManager');
    }

    /**
     * Обертка для кеша для доктрины
     *
     * @return \Doctrine\Common\Cache\Cache $doctrineCache
     */
    private function getDoctrineCache()
    {
        return $this->serviceLocator->get('doctrine.cache.zend.static.local');
    }

    /**
     * Плагин получения типа вкладки
     *
     * @return Plugin\TabType
     */
    private function getTabTypePlugin()
    {
        return new Plugin\TabType();
    }

    /**
     * Плагин фильтрации по городу
     *
     * @return Plugin\City
     */
    private function getCityPlugin()
    {
        return new Plugin\City();
    }

    /**
     * Плагин фильтрации по доходу
     *
     * @return Plugin\Money
     */
    private function getMoneyPlugin()
    {
        return new Plugin\Money();
    }

    /**
     * Плагин фильтрации по владельцу
     *
     * @return Plugin\Owner
     */
    private function getOwnerPlugin()
    {
        /** @var SubscriptionService $subscriptionService */
        $subscriptionService = $this->serviceLocator->get('SubscriptionService');

        return new Plugin\Owner(
            $this->getIdentity(),
            new CacheAdapter\Plugin\User(
                $this->getDoctrineCache(),
                new FeedPluginAdapter\User($this->getEntityManager())
            ),
            new CacheAdapter\GetList\Subscriber(
                $this->getDoctrineCache(),
                new Adapter\Subscriber($subscriptionService)
            )
        );
    }

    /**
     * Плагин фильтрации по продукту
     *
     * @return Plugin\TagProduct
     */
    private function getTagProductPlugin()
    {
        return new Plugin\TagProduct();
    }

    /**
     * Плагин фильтрации по интересам
     *
     * @return Plugin\TagNiche
     */
    private function getTagNicheProductPlugin()
    {
        return new Plugin\TagNiche($this->getIdentity());
    }

    /**
     * Плагин для определения смещения
     *
     * @return Plugin\Offset
     */
    private function getOffsetPlugin()
    {
        return new Plugin\Offset();
    }

    /**
     * Плагин для сортировки по полезности
     *
     * @return Plugin\Useful
     */
    private function getUsefulPlugin()
    {
        return new Plugin\Useful();
    }

    /**
     * Плагин определения просматривает ли ленту админ
     *
     * @return Plugin\Admin
     */
    private function getIsAdminPlugin()
    {
        return new Plugin\Admin($this->getSecurityService()->isAdmin());
    }

    /**
     * Плагин ограничения первой страницы для правильной пагинации
     *
     * @return Plugin\LimitByFirstPageDate
     */
    private function getLimitByFirstPageDatePlugin()
    {
        /** @var \Feed\Repository\Feed $feedRepository */
        $feedRepository = $this->getEntityManager()->getRepository('Feed\Entity\Feed');
        return new Plugin\LimitByFirstPageDate(new Adapter\LimitMaxByDate($feedRepository, new DateTime()));
    }

    /**
     * Плагин ограничения максимального поста, чтобы нельзя было мотать ленту до бесконечности
     *
     * @return Plugin\LimitByMaxIntervalDate
     */
    private function getLimitByMaxIntervalDatePlugin()
    {
        /** @var \Feed\Repository\Feed $feedRepository */
        $feedRepository = $this->getEntityManager()->getRepository('Feed\Entity\Feed');

        return new Plugin\LimitByMaxIntervalDate(
            new Adapter\LimitMinByDate(
                $feedRepository,
                new DateTime('-2 weeks')
            )
        );
    }
}
