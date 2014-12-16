<?php

namespace FeedTest\Unit\Service\FeedPlugin\Plugin\Filter;

use Feed\Service\Feed\GetList\Entity\Collection;
use Feed\Service\Feed\Plugin\Plugin\Filter\Mock;

/**
 * Лента постов / Тестирование заглушки фильтра
 *
 * Class MockTest
 * @package FeedTest\Unit\Service\FeedPlugin\Plugin\Filter
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
        $this->plugin = new Mock(1, true);
        $this->assertEquals(1, $this->plugin->getType());

        $this->plugin = new Mock(2, true);
        $this->assertEquals(2, $this->plugin->getType());
    }

    /**
     * Должен примениться
     */
    public function testShouldStart()
    {
        $this->plugin = new Mock(1, true);
        $this->assertEquals(true, $this->plugin->shouldStart());

        $this->plugin = new Mock(1, false);
        $this->assertEquals(false, $this->plugin->shouldStart());
    }

    /**
     * Применить плагин
     */
    public function testApply()
    {
        $collection1 = new Collection();
        $collection2 = new Collection();

        $this->plugin = new Mock(1, true, $collection1);
        $this->assertTrue($collection1 === $this->plugin->apply($collection2));

        $this->plugin = new Mock(1, true);
        $this->assertTrue($collection2 === $this->plugin->apply($collection2));
    }
}