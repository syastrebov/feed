<?php

namespace FeedTest\Unit\Service\Feed\GetList\Entity;

use Feed\Service\Feed\GetList\Entity\Collection;
use Feed\Service\Feed\GetList\Entity\Response as ResponseEntity;

/**
 * Лента постов / Тестирование ответа сборщика ленты
 *
 * Class ResponseTest
 * @package FeedTest\Unit\Service\Feed\Entity
 */
class ResponseTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var ResponseEntity
     */
    private $response;

    /**
     *
     */
    public function tearDown()
    {
        $this->response = null;
    }

    /**
     * Задать коллекцию объектов
     */
    public function testSetCollection()
    {
        $collection = new Collection();

        $this->response = new ResponseEntity($collection, 0, false);
        $this->assertTrue($collection === $this->response->getCollection());

        $collection2 = new Collection();

        $this->response = new ResponseEntity($collection2, 0, false);
        $this->assertFalse($collection === $this->response->getCollection());
    }

    /**
     * Смещение
     *
     * @param mixed $offset
     * @param int   $expected
     *
     * @dataProvider providerOffset
     */
    public function testOffset($offset, $expected)
    {
        $this->response = new ResponseEntity(new Collection(), $offset, false);
        $this->assertTrue($expected === $this->response->getOffset());
    }

    /**
     * Есть еще посты
     *
     * @param mixed $hasMore
     * @param bool  $expected
     *
     * @dataProvider providerHasMore
     */
    public function testHasMore($hasMore, $expected)
    {
        $this->response = new ResponseEntity(new Collection(), 0, $hasMore);
        $this->assertTrue($expected === $this->response->hasMore());
    }

    /**
     * Смещение
     *
     * @return array
     */
    public function providerOffset()
    {
        return [
            [0, 0],
            [false, 0],
            [null, 0],
            [10, 10],
        ];
    }

    /**
     * Есть еще посты
     *
     * @return array
     */
    public function providerHasMore()
    {
        return [
            [0, false],
            [false, false],
            [null, false],
            [10, true],
        ];
    }
}