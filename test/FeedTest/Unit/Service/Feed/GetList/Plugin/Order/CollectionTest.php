<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Order;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Plugin\Order\Collection;
use Feed\Service\Feed\GetList\Plugin\Order\Mock;
use Feed\Service\Feed\GetList\Adapter\Mock\Adapter as MockAdapter;

/**
 * Лента постов / Тестирование коллекции сортировок
 *
 * Class CollectionTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Order
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
            ->attach(new Mock(new MockAdapter(), new Configuration(), 1, false))
            ->attach(new Mock(new MockAdapter(), new Configuration(), 1, false));
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

    /**
     * Получение списка добавленных типов
     */
    public function testGetTypes()
    {
        $this->collection
            ->attach(new Mock(new MockAdapter(), new Configuration(), 1, false))
            ->attach(new Mock(new MockAdapter(), new Configuration(), 2, false));

        $this->assertEquals([1, 2], $this->collection->getTypes());
    }
}