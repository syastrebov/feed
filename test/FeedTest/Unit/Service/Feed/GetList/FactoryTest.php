<?php

namespace FeedTest\Unit\Service\Feed\GetList;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Factory as ServiceFactory;
use Feed\Service\Feed\GetList\Plugin\Filter\FeedFilterInterface;
use Feed\Service\Feed\GetList\Plugin\Order\FeedOrderInterface;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Лента постов / Тестирование фабрики создания сервиса
 *
 * Class ServiceTest
 * @package FeedTest\Unit\Service\Feed\Factory
 */
class FactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * @var ServiceFactory
     */
    private $factory;

    /**
     *
     */
    public function setUp()
    {
        $applicationMock = $this->getMock('Zend\EventManager\EventsCapableInterface', ['getEventManager']);
        $applicationMock
            ->expects($this->any())
            ->method('getEventManager')
            ->will($this->returnValue($this->getMock('Zend\EventManager\EventManagerInterface')));

        $this->serviceLocator = $this->getMock('Zend\ServiceManager\ServiceLocatorInterface', ['set', 'get', 'has']);
        $this->serviceLocator
            ->expects($this->at(0))
            ->method('get')
            ->with($this->equalTo('Doctrine\ORM\EntityManager'))
            ->will($this->returnValue($this->getMock('Doctrine\ORM\EntityManagerInterface')));
        $this->serviceLocator
            ->expects($this->at(1))
            ->method('get')
            ->with($this->equalTo('Application'))
            ->will($this->returnValue($applicationMock));

        $this->factory = new ServiceFactory();
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->factory = null;
    }

    /**
     * Проверка создания правильного сервиса
     */
    public function testCreateService()
    {
        $this->assertInstanceOf(
            'Feed\Service\Feed\GetList\Service',
            $this->factory->createService($this->serviceLocator)
        );
    }

    /**
     * Проверка наличия всех закладок
     */
    public function testCheckTabs()
    {
        $service = $this->factory->createService($this->serviceLocator);
        $configuration = new Configuration();

        $this->assertNotNull(
            $this->invokeMethod($service, 'getTab', [$configuration->setTabType(FeedTabInterface::TYPE_ALL)])
        );
        $this->assertNotNull(
            $this->invokeMethod($service, 'getTab', [$configuration->setTabType(FeedTabInterface::TYPE_MY)])
        );
        $this->assertNotNull(
            $this->invokeMethod($service, 'getTab', [$configuration->setTabType(FeedTabInterface::TYPE_PROFILE)])
        );
    }

    /**
     * Проверка что все фильтры и сорировки добавлены для вкладки реки
     */
    public function testTabAll()
    {
        $service = $this->factory->createService($this->serviceLocator);
        $configuration = new Configuration();

        /** @var \Feed\Service\Feed\GetList\Tab\Tab $tab */
        $tab = $this->invokeMethod($service, 'getTab', [$configuration->setTabType(FeedTabInterface::TYPE_ALL)]);
        $this->assertInstanceOf('Feed\Service\Feed\GetList\Tab\Tab', $tab);

        $this->assertEquals([
            FeedFilterInterface::TYPE_CITY,
            FeedFilterInterface::TYPE_MONEY,
            FeedFilterInterface::TYPE_TAG_PRODUCT,
            FeedFilterInterface::TYPE_TAG_NICHE,
            FeedFilterInterface::TYPE_IS_SIBERIA,
            FeedFilterInterface::TYPE_IS_DELETED,
            FeedFilterInterface::TYPE_LIMIT_BY_FIRST_PAGE_DATE,
            FeedFilterInterface::TYPE_LIMIT_BY_MAX_INTERVAL_DATE,

        ], $tab->getFilterTypes());

        $this->assertEquals([
            FeedOrderInterface::TYPE_DATE,
            FeedOrderInterface::TYPE_USEFUL,

        ], $tab->getOrderTypes());
    }

    /**
     * Проверка что все фильтры и сорировки добавлены для вкладки мои записи и моих избранных
     */
    public function testTabMy()
    {
        $service = $this->factory->createService($this->serviceLocator);
        $configuration = new Configuration();

        /** @var \Feed\Service\Feed\GetList\Tab\Tab $tab */
        $tab = $this->invokeMethod($service, 'getTab', [$configuration->setTabType(FeedTabInterface::TYPE_MY)]);
        $this->assertInstanceOf('Feed\Service\Feed\GetList\Tab\Tab', $tab);

        $this->assertEquals([
            FeedFilterInterface::TYPE_OWNER,
            FeedFilterInterface::TYPE_IS_SIBERIA,
            FeedFilterInterface::TYPE_IS_DELETED,
            FeedFilterInterface::TYPE_LIMIT_BY_FIRST_PAGE_DATE,
            FeedFilterInterface::TYPE_LIMIT_BY_MAX_INTERVAL_DATE,

        ], $tab->getFilterTypes());

        $this->assertEquals([
            FeedOrderInterface::TYPE_DATE,
            FeedOrderInterface::TYPE_USEFUL,

        ], $tab->getOrderTypes());
    }

    /**
     * Проверка что все фильтры и сорировки добавлены для вкладки профиля
     */
    public function testTabProfile()
    {
        $service = $this->factory->createService($this->serviceLocator);
        $configuration = new Configuration();

        /** @var \Feed\Service\Feed\GetList\Tab\Tab $tab */
        $tab = $this->invokeMethod($service, 'getTab', [$configuration->setTabType(FeedTabInterface::TYPE_PROFILE)]);
        $this->assertInstanceOf('Feed\Service\Feed\GetList\Tab\Tab', $tab);

        $this->assertEquals([
            FeedFilterInterface::TYPE_OWNER,
            FeedFilterInterface::TYPE_IS_DELETED,
            FeedFilterInterface::TYPE_LIMIT_BY_FIRST_PAGE_DATE,

        ], $tab->getFilterTypes());

        $this->assertEquals([
            FeedOrderInterface::TYPE_DATE,
            FeedOrderInterface::TYPE_USEFUL,

        ], $tab->getOrderTypes());
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    private function invokeMethod(&$object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
