<?php

namespace FeedTest\Unit\Service\Feed\GetList\Entity;

use Feed\Service\Feed\GetList\Entity\Collection;

/**
 * Лента постов / Тестирование коллекции постов
 *
 * Class CollectionTest
 * @package FeedTest\Unit\Service\Feed\Entity
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
     * Плагин не найден
     *
     * @expectedException \Exception
     */
    public function testNotFound()
    {
        $this->collection->getById(1);
    }

    /**
     * Получение объекта по id
     */
    public function testGetById()
    {
        $feedEntity = $this->getEntityMock(1);
        $this->collection->attach($feedEntity);
        $this->assertEquals($feedEntity, $this->collection->getById(1));
    }

    /**
     * Объект с этим id уже существует
     *
     * @expectedException \Exception
     */
    public function testAlreadyExists()
    {
        $feedEntity = $this->getEntityMock(1);
        $this->collection->attach($feedEntity)->attach($feedEntity);
    }

    /**
     * Преобразовать коллекцию в массив
     */
    public function testToArrayEntities()
    {
        $this->collection
            ->attach($this->getEntityMock(1))
            ->attach($this->getEntityMock(2))
            ->toArrayEntities();
    }

    /**
     * Удаление поста из коллекции
     */
    public function testDetach()
    {
        $this->assertEquals(2, $this->collection
            ->attach($this->getEntityMock(1))
            ->attach($this->getEntityMock(2))
            ->attach($this->getEntityMock(3))
            ->detach(3)
            ->pop()
            ->getId()
        );
    }

    /**
     * Попытка удалить несуществующий объект
     *
     * @expectedException \Exception
     */
    public function testDetachNotFound()
    {
        $this->collection->detach(1);
    }

    /**
     * Возвращает mock модели поста
     *
     * @param int $id
     * @return \Feed\Entity\Feed
     */
    private function getEntityMock($id)
    {
        $entity = $this->getMock('Feed\Entity\Feed');
        $entity
            ->expects($this->any())
            ->method('getId')
            ->will($this->returnValue($id));

        return $entity;
    }
}