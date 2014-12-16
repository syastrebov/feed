<?php

namespace FeedTest\Unit\Service\Feed\Tab;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Plugin\Filter;
use Feed\Service\Feed\GetList\Plugin\Order;
use Feed\Service\Feed\GetList\Tab\Tab as FeedTab;
use Feed\Service\Feed\GetList\Adapter\Mock;
use Feed\Service\Feed\GetList\Entity\Collection as FeedCollection;

/**
 * Лента постов / Тестирование контейнера фильтров для ленты
 *
 * Class TabTest
 * @package FeedTest\Unit\Service\Feed\Tab
 */
class TabTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Filter\Collection
     */
    private $filters;

    /**
     * @var Order\Collection
     */
    private $orders;

    /**
     * @var FeedTab
     */
    private $tab;

    /**
     *
     */
    public function setUp()
    {
        $this->filters = new Filter\Collection();
        $this->orders  = new Order\Collection();
        $this->tab     = new FeedTab(1, $this->filters, $this->orders);
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->tab     = null;
        $this->filters = null;
        $this->orders  = null;
    }

    /**
     * Тип контейнера
     *
     * @param $type
     * @dataProvider providerGetType
     */
    public function testGetType($type)
    {
        $this->tab = new FeedTab($type, $this->filters, $this->orders);
        $this->assertEquals($type, $this->tab->getType());
    }

    /**
     * Тип контейнера (недопустимый тип)
     *
     * @param $type
     * @expectedException \Exception
     * @dataProvider providerInvalidType
     */
    public function testInvalidType($type)
    {
        $this->tab = new FeedTab($type, $this->filters, $this->orders);
    }

    /**
     * Плагины должны примениться
     */
    public function testShouldStart()
    {
        $mock1 = new Filter\Mock(new Mock\Adapter(), new Configuration(), 1, true);
        $mock2 = new Filter\Mock(new Mock\Adapter(), new Configuration(), 2, true);

        $mock3 = new Order\Mock(new Mock\Adapter(), new Configuration(), 1, true);
        $mock4 = new Order\Mock(new Mock\Adapter(), new Configuration(), 2, true);

        $this->filters->attach($mock1)->attach($mock2);
        $this->orders->attach($mock3)->attach($mock4);

        $this->tab->modify(new Configuration(), new Mock\Select(new FeedCollection()));

        $this->assertTrue($mock1->isApplied());
        $this->assertTrue($mock2->isApplied());
        $this->assertTrue($mock3->isApplied());
        $this->assertTrue($mock4->isApplied());
    }

    /**
     * Плагины должны быть пропущены
     */
    public function testShouldSkip()
    {
        $mock1 = new Filter\Mock(new Mock\Adapter(), new Configuration(), 1, true);
        $mock2 = new Filter\Mock(new Mock\Adapter(), new Configuration(), 2, false);

        $mock3 = new Order\Mock(new Mock\Adapter(), new Configuration(), 1, true);
        $mock4 = new Order\Mock(new Mock\Adapter(), new Configuration(), 2, false);

        $this->filters->attach($mock1)->attach($mock2);
        $this->orders->attach($mock3)->attach($mock4);

        $this->tab->modify(new Configuration(), new Mock\Select(new FeedCollection()));

        $this->assertTrue($mock1->isApplied());
        $this->assertFalse($mock2->isApplied());
        $this->assertTrue($mock3->isApplied());
        $this->assertFalse($mock4->isApplied());
    }

    /**
     * Объект запроса проходит сковзным через фильтры и сортировки
     */
    public function testPassThrowSelect()
    {
        $select = new Mock\Select(new FeedCollection());

        $this->filters
            ->attach(new Filter\Mock(new Mock\Adapter(), new Configuration(), 1, true))
            ->attach(new Filter\Mock(new Mock\Adapter(), new Configuration(), 2, true));
        $this->orders
            ->attach(new Order\Mock(new Mock\Adapter(), new Configuration(), 1, true))
            ->attach(new Order\Mock(new Mock\Adapter(), new Configuration(), 2, true));

        $modifiedSelect = $this->tab->modify(new Configuration(), $select);
        $this->assertTrue($select === $modifiedSelect);
    }

    /**
     * Объект запроса подменяется в фильтре
     */
    public function testChangeSelectInFilter()
    {
        $select  = new Mock\Select(new FeedCollection());
        $select2 = new Mock\Select(new FeedCollection());

        $this->filters
            ->attach(new Filter\Mock(new Mock\Adapter(), new Configuration(), 1, true))
            ->attach(new Filter\Mock(new Mock\Adapter($select2), new Configuration(), 2, true));
        $this->orders
            ->attach(new Order\Mock(new Mock\Adapter(), new Configuration(), 1, true))
            ->attach(new Order\Mock(new Mock\Adapter(), new Configuration(), 2, true));

        $modifiedSelect = $this->tab->modify(new Configuration(), $select);

        $this->assertFalse($select === $select2);
        $this->assertTrue($select2 === $modifiedSelect);
    }

    /**
     * Объект запроса подменяется в сортировке
     */
    public function testChangeSelectInOrder()
    {
        $select  = new Mock\Select(new FeedCollection());
        $select2 = new Mock\Select(new FeedCollection());

        $this->filters
            ->attach(new Filter\Mock(new Mock\Adapter(), new Configuration(), 1, true))
            ->attach(new Filter\Mock(new Mock\Adapter(), new Configuration(), 2, true));
        $this->orders
            ->attach(new Order\Mock(new Mock\Adapter(), new Configuration(), 1, true))
            ->attach(new Order\Mock(new Mock\Adapter($select2), new Configuration(), 2, true));

        $modifiedSelect = $this->tab->modify(new Configuration(), $select);

        $this->assertFalse($select === $select2);
        $this->assertTrue($select2 === $modifiedSelect);
    }

    /**
     * Тип контейнера
     *
     * @return array
     */
    public function providerGetType()
    {
        return [[1], [2], [3], ['type']];
    }

    /**
     * Тип контейнера (недопустимый тип)
     *
     * @return array
     */
    public function providerInvalidType()
    {
        return [[0], [null]];
    }
}