<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Plugin\Content;

use Feed\Entity\Feed;
use Feed\Service\Feed\GetList\Entity\Collection;
use Feed\Service\Feed\Plugin\Plugin\Content\Mock;

/**
 * Лента постов / Тестирование плагина контента
 *
 * Class MockTest
 * @package FeedTest\Unit\Service\FeedPlugin\Plugin\Content
 */
class MockTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Mock
     */
    private $plugin;

    /**
     *
     */
    public function tearDown()
    {
        $this->plugin = null;
    }

    /**
     * Тип плагина
     */
    public function testGetType()
    {
        $this->plugin = new Mock(1);
        $this->assertEquals(1, $this->plugin->getType());

        $this->plugin = new Mock(2);
        $this->assertEquals(2, $this->plugin->getType());
    }

    /**
     * Применить плагин к коллекции
     */
    public function testApply()
    {
        $collection1 = new Collection();
        $collection2 = new Collection();

        $this->plugin = new Mock(1, $collection1);
        $this->assertTrue($collection1 === $this->plugin->apply($collection2));

        $this->plugin = new Mock(1);
        $this->assertTrue($collection2 === $this->plugin->apply($collection2));
    }

    /**
     * Применить плагин к модели
     */
    public function testApplyEntity()
    {
        $entity1 = new Feed();
        $entity2 = new Feed();

        $this->plugin = new Mock(1, null, $entity1);
        $this->assertTrue($entity1 === $this->plugin->applyEntity($entity2));

        $this->plugin = new Mock(1);
        $this->assertTrue($entity2 === $this->plugin->applyEntity($entity2));
    }
} 