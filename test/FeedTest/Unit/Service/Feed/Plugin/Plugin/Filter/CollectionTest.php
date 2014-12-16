<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Plugin\Filter;

use Feed\Service\Feed\Plugin\Plugin\Filter\Collection;
use Feed\Service\Feed\Plugin\Plugin\Filter\Mock;

/**
 * Лента постов / Тестирование коллекции плагинов фильтрации постов
 *
 * Class CollectionTest
 * @package FeedTest\Unit\Service\FeedPlugin\Plugin\Filter
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
    public function testAlreadyExist()
    {
        $this->collection
            ->attach(new Mock(1, false))
            ->attach(new Mock(1, false));
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
            ->attach(new Mock(1, false))
            ->attach(new Mock(2, false));

        $this->assertEquals([1, 2], $this->collection->getTypes());
    }
}