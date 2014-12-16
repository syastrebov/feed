<?php

namespace Feed\Service\Feed\GetList;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Plugin;
use Feed\Service\Feed\GetList\Tab\Collection as TabCollection;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;
use Feed\Service\Feed\GetList\Tab\Tab as FeedTab;
use Feed\Service\Feed\GetList\Adapter\Doctrine as Adapter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Zend\EventManager\EventManagerInterface;

/**
 * Лента постов / Фабрика создания сервиса ленты
 *
 * Class Service
 * @package Application\Service\Feed\Factory
 */
class Factory implements FactoryInterface
{
    /**
     * Создаем сервис
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return Service
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $serviceLocator->get('Doctrine\ORM\EntityManager');
        /** @var EventManagerInterface $eventManager */
        $eventManager  = $serviceLocator->get('Application')->getEventManager();

        $collection = new TabCollection();
        $collection
            ->attach($this->getFeedAllTab())
            ->attach($this->getFeedMyTab())
            ->attach($this->getFeedProfileTab())
            ->attach($this->getFeedProductTab())
            ->attach($this->getFeedNicheTab());

        return new Service(
            new Adapter\Select($entityManager),
            new Adapter\Insert($entityManager),
            $collection,
            $eventManager
        );
    }

    /**
     * Вкладка реки
     *
     * @return FeedTab
     */
    private function getFeedAllTab()
    {
        $filterCollection = new Plugin\Filter\Collection();
        $filterCollection
            ->attach($this->getCityFilterPlugin())
            ->attach($this->getMoneyFilterPlugin())
            ->attach($this->getTagProductFilterPlugin())
            ->attach($this->getTagNicheFilterPlugin())
            ->attach($this->getSiberiaFilterPlugin())
            ->attach($this->getDeletedFilterPlugin())
            ->attach($this->getLimitByFirstPageDateFilterPlugin())
            ->attach($this->getLimitByMaxIntervalDateFilterPlugin());

        $orderCollection = new Plugin\Order\Collection();
        $orderCollection
            ->attach($this->getDateOrderPlugin())
            ->attach($this->getUsefulOrderPlugin());

        return new FeedTab(FeedTabInterface::TYPE_ALL, $filterCollection, $orderCollection);
    }

    /**
     * Вкладка мои записи и моих подписчиков
     *
     * @return FeedTab
     */
    private function getFeedMyTab()
    {
        $filterCollection = new Plugin\Filter\Collection();
        $filterCollection
            ->attach($this->getOwnerFilterPlugin())
            ->attach($this->getSiberiaFilterPlugin())
            ->attach($this->getDeletedFilterPlugin())
            ->attach($this->getLimitByFirstPageDateFilterPlugin())
            ->attach($this->getLimitByMaxIntervalDateFilterPlugin());

        $orderCollection = new Plugin\Order\Collection();
        $orderCollection
            ->attach($this->getDateOrderPlugin())
            ->attach($this->getUsefulOrderPlugin());

        return new FeedTab(FeedTabInterface::TYPE_MY, $filterCollection, $orderCollection);
    }

    /**
     * Вкладка профиль
     *
     * @return FeedTab
     */
    private function getFeedProfileTab()
    {
        $filterCollection = new Plugin\Filter\Collection();
        $filterCollection
            ->attach($this->getOwnerFilterPlugin())
            ->attach($this->getDeletedFilterPlugin())
            ->attach($this->getLimitByFirstPageDateFilterPlugin());

        $orderCollection = new Plugin\Order\Collection();
        $orderCollection
            ->attach($this->getDateOrderPlugin())
            ->attach($this->getUsefulOrderPlugin());

        return new FeedTab(FeedTabInterface::TYPE_PROFILE, $filterCollection, $orderCollection);
    }

    /**
     * Вкладка выбранного продукта
     *
     * @return FeedTab
     */
    private function getFeedProductTab()
    {
        $filterCollection = new Plugin\Filter\Collection();
        $filterCollection
            ->attach($this->getTagProductFilterPlugin())
            ->attach($this->getDeletedFilterPlugin())
            ->attach($this->getLimitByFirstPageDateFilterPlugin())
            ->attach($this->getLimitByMaxIntervalDateFilterPlugin());

        $orderCollection = new Plugin\Order\Collection();
        $orderCollection
            ->attach($this->getDateOrderPlugin())
            ->attach($this->getUsefulOrderPlugin());

        return new FeedTab(FeedTabInterface::TYPE_PRODUCT, $filterCollection, $orderCollection);
    }

    /**
     * Вкладка выбранного интереса
     *
     * @return FeedTab
     */
    private function getFeedNicheTab()
    {
        $filterCollection = new Plugin\Filter\Collection();
        $filterCollection
            ->attach($this->getTagNicheFilterPlugin())
            ->attach($this->getDeletedFilterPlugin())
            ->attach($this->getLimitByFirstPageDateFilterPlugin())
            ->attach($this->getLimitByMaxIntervalDateFilterPlugin());

        $orderCollection = new Plugin\Order\Collection();
        $orderCollection
            ->attach($this->getDateOrderPlugin())
            ->attach($this->getUsefulOrderPlugin());

        return new FeedTab(FeedTabInterface::TYPE_NICHE, $filterCollection, $orderCollection);
    }

    /**
     * Фильтр по владельцам (чьи посты можно загружать)
     *
     * @return Plugin\Filter\Owner
     */
    private function getOwnerFilterPlugin()
    {
        return new Plugin\Filter\Owner(new Adapter\Filter\Owner(), new Configuration());
    }

    /**
     * Фильтр по городу
     *
     * @return Plugin\Filter\City
     */
    private function getCityFilterPlugin()
    {
        return new Plugin\Filter\City(new Adapter\Filter\City(), new Configuration());
    }

    /**
     * Фильтр по доходу
     *
     * @return Plugin\Filter\Money
     */
    private function getMoneyFilterPlugin()
    {
        return new Plugin\Filter\Money(new Adapter\Filter\Money(), new Configuration());
    }

    /**
     * Фильтр по продукту
     *
     * @return Plugin\Filter\TagProduct
     */
    private function getTagProductFilterPlugin()
    {
        return new Plugin\Filter\TagProduct(new Adapter\Filter\TagProduct(), new Configuration());
    }

    /**
     * Фильтр по интересам
     *
     * @return Plugin\Filter\TagNiche
     */
    private function getTagNicheFilterPlugin()
    {
        return new Plugin\Filter\TagNiche(new Adapter\Filter\TagNiche(), new Configuration());
    }

    /**
     * Фильтр "отправленные в Сибирь"
     *
     * @return Plugin\Filter\Siberia
     */
    private function getSiberiaFilterPlugin()
    {
        return new Plugin\Filter\Siberia(new Adapter\Filter\Siberia(), new Configuration());
    }

    /**
     * Фильтр "скрывать удаленные посты"
     *
     * @return Plugin\Filter\Deleted
     */
    private function getDeletedFilterPlugin()
    {
        return new Plugin\Filter\Deleted(new Adapter\Filter\Deleted(), new Configuration());
    }

    /**
     * Фильтр для правильной постраничной пагинации с учетом добавления новых постов
     *
     * @return Plugin\Filter\LimitByFirstPageDate
     */
    private function getLimitByFirstPageDateFilterPlugin()
    {
        return new Plugin\Filter\LimitByFirstPageDate(new Adapter\Filter\LimitByFirstPageDate(), new Configuration());
    }

    /**
     * Фильтр ограничитель по смещению по интервалу времени
     *
     * @return Plugin\Filter\LimitByMaxIntervalDate
     */
    private function getLimitByMaxIntervalDateFilterPlugin()
    {
        return new Plugin\Filter\LimitByMaxIntervalDate(new Adapter\Filter\LimitByMaxIntervalDate(), new Configuration());
    }

    /**
     * Сортировка по городу
     *
     * @return Plugin\Order\Date
     */
    private function getDateOrderPlugin()
    {
        return new Plugin\Order\Date(new Adapter\Order\Date(), new Configuration());
    }

    /**
     * Сортировка по полезности
     *
     * @return Plugin\Order\Useful
     */
    private function getUsefulOrderPlugin()
    {
        return new Plugin\Order\Useful(new Adapter\Order\Useful(), new Configuration());
    }
}
