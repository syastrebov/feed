<?php

namespace FeedTest\Unit\Service\Feed\GetList;

use Feed\Service\Feed\GetList\Adapter\Mock as Adapter;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Service;
use Feed\Service\Feed\GetList\Tab\Collection as TabCollection;
use Feed\Service\Feed\GetList\Entity\Collection as FeedCollection;
use Feed\Service\Feed\GetList\Plugin;
use Feed\Service\Feed\GetList\Tab\Tab;
use Zend\EventManager\EventManager;

/**
 * Лента постов / Тестирование сервиса
 *
 * Class ServiceTest
 * @package FeedTest\Unit\Service\Feed
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TabCollection
     */
    private $tabs;

    /**
     * @var Service
     */
    private $service;

    /**
     * @var EventManager
     */
    private $eventManager;

    /**
     *
     */
    public function setUp()
    {
        $this->tabs = new TabCollection();
        $this->tabs->attach(new Tab(1, new Plugin\Filter\Collection(), new Plugin\Order\Collection()));

        $collection         = new FeedCollection();
        $this->eventManager = new EventManager;
        $this->service      = new Service(new Adapter\Select($collection), new Adapter\Insert($collection), $this->tabs, $this->eventManager);
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->tabs    = null;
        $this->service = null;
    }

    /**
     * Тестирование получения списка
     */
    public function testGetList()
    {
        $feedEntity = $this->getMock('Feed\Entity\Feed');
        $feedEntity
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $feedEntity2 = $this->getMock('Feed\Entity\Feed');
        $feedEntity2
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        /** @var \Feed\Entity\Feed $feedEntity */
        /** @var \Feed\Entity\Feed $feedEntity2 */

        $collection = new FeedCollection();
        $collection
            ->attach($feedEntity)
            ->attach($feedEntity2);

        $this->service = new Service(
            new Adapter\Select(clone $collection),
            new Adapter\Insert($collection),
            $this->tabs,
            $this->eventManager
        );
        $this->assertEquals(2, count(
            $this->service
                ->getList(Configuration::create()->setTabType(1))
                ->getCollection()
        ));
    }

    /**
     * Статус есть ли еще записи в списке
     */
    public function testHasMore()
    {
        $feedEntity = $this->getMock('Feed\Entity\Feed');
        $feedEntity
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $feedEntity2 = $this->getMock('Feed\Entity\Feed');
        $feedEntity2
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        /** @var \Feed\Entity\Feed $feedEntity */
        /** @var \Feed\Entity\Feed $feedEntity2 */

        $collection = new FeedCollection();
        $collection
            ->attach($feedEntity)
            ->attach($feedEntity2);

        $this->service = new Service(
            new Adapter\Select(clone $collection),
            new Adapter\Insert($collection),
            $this->tabs,
            $this->eventManager
        );

        $config = Configuration::create()->setTabType(1);
        $response = $this->service->getList($config);

        $this->assertEquals(0, $config->getLimit());
        $this->assertEquals(2, count($response->getCollection()));
        $this->assertFalse($response->hasMore());

        $this->service = new Service(
            new Adapter\Select(clone $collection),
            new Adapter\Insert($collection),
            $this->tabs,
            $this->eventManager
        );

        $config = Configuration::create()->setTabType(1)->setLimit(1);
        $response = $this->service->getList($config);

        $this->assertEquals(1, $config->getLimit());
        $this->assertEquals(1, count($response->getCollection()));
        $this->assertTrue($response->hasMore());

        $this->service = new Service(
            new Adapter\Select(clone $collection),
            new Adapter\Insert($collection),
            $this->tabs,
            $this->eventManager
        );

        $config = Configuration::create()->setTabType(1)->setLimit(5);
        $response = $this->service->getList($config);

        $this->assertEquals(5, $config->getLimit());
        $this->assertEquals(2, count($response->getCollection()));
        $this->assertFalse($response->hasMore());
    }

    /**
     * Тестирование добавления элемента
     */
    public function testInsert()
    {
        $feedEntity = $this->getMock('Feed\Entity\Feed');
        $feedEntity
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(1));

        $feedEntity2 = $this->getMock('Feed\Entity\Feed');
        $feedEntity2
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue(2));

        /** @var \Feed\Entity\Feed $feedEntity */
        /** @var \Feed\Entity\Feed $feedEntity2 */

        $collection = new FeedCollection();

        $this->service = new Service(new Adapter\Select($collection), new Adapter\Insert($collection), $this->tabs, $this->eventManager);
        $this->assertEquals(0, count(
            $this->service
                ->getList(Configuration::create()->setTabType(1)->setLimit(10))
                ->getCollection()
        ));

        $this->service->insert($feedEntity)->insert($feedEntity2);
        $this->assertEquals(2, count(
            $this->service
                ->getList(Configuration::create()->setTabType(1)->setLimit(10))
                ->getCollection()
        ));
    }
}