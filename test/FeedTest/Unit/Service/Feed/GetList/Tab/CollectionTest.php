<?php

namespace FeedTest\Unit\Service\Feed\Tab;

use Feed\Service\Feed\GetList\Tab\Collection;
use Feed\Service\Feed\GetList\Tab\Tab;
use Feed\Service\Feed\GetList\Plugin\Filter;
use Feed\Service\Feed\GetList\Plugin\Order;

/**
 * Лента постов / Тестирование коллекции контейнеров
 *
 * Class CollectionTest
 * @package FeedTest\Unit\Service\Feed\Tab
 */
class CollectionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Collection
     */
    private $collection;

    /**
     *
     */
    public function setUp()
    {
        $this->collection = new Collection();
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->collection = null;
    }

    /**
     * Плагин с этим типом уже существует
     *
     * @expectedException \Exception
     */
    public function testAlreadyExists()
    {
        $this->collection
            ->attach(new Tab(1, new Filter\Collection(), new Order\Collection()))
            ->attach(new Tab(1, new Filter\Collection(), new Order\Collection()));
    }

    /**
     * Плагин не найден
     *
     * @expectedException \Exception
     */
    public function testNotFound()
    {
        $this->collection->getByType(1);
    }
}