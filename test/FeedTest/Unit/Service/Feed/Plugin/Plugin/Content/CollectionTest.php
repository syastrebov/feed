<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Plugin\Content;

use Feed\Service\Feed\Plugin\Plugin\Content\Collection;
use Feed\Service\Feed\Plugin\Plugin\Content\Mock;

/**
 * Лента постов / Тестирование коллекции плагинов заполнения контента для постов
 *
 * Class CollectionTest
 * @package FeedTest\Unit\Service\FeedPlugin\Plugin\Content
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
            ->attach(new Mock(1))
            ->attach(new Mock(1));
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
            ->attach(new Mock(1))
            ->attach(new Mock(2));

        $this->assertEquals([1, 2], $this->collection->getTypes());
    }
}