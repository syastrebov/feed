<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Order;

use Feed\Service\Feed\GetList\Plugin\Order\Date;
use Feed\Service\Feed\GetList\Adapter\Mock\Adapter as MockAdapter;
use Feed\Service\Feed\GetList\Plugin\Order\FeedOrderInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Тестирование сортировки по дате
 *
 * Class DateTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Order
 */
class DateTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $defaultConfig;

    /**
     * @var Date
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->defaultConfig = new Configuration();
        $this->plugin        = new Date(new MockAdapter(), $this->defaultConfig);
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->defaultConfig = null;
        $this->plugin        = null;
    }

    /**
     * Проверка типа плагина
     */
    public function testGetType()
    {
        $this->assertEquals(FeedOrderInterface::TYPE_DATE, $this->plugin->getType());
    }

    /**
     * Плагин должен запуститься
     */
    public function testShouldStart()
    {
        $configuration = new Configuration();
        $configuration->setSortUseful(false);

        $this->assertTrue($this->plugin->shouldStart());
        $this->assertTrue($this->plugin->setConfiguration($configuration)->shouldStart());
    }

    /**
     * Клонирование конфигурации при передаче через метод
     */
    public function testCloneSetConfiguration()
    {
        $configuration = new Configuration();
        $this->plugin->setConfiguration($configuration);

        $configuration->setSortUseful(true);
        $this->assertTrue($this->plugin->shouldStart());
    }

    /**
     * Клонирование конфигурации при передаче через конструктор
     */
    public function testConstructCloneConfiguration()
    {
        $this->defaultConfig->setSortUseful(true);
        $this->assertTrue($this->plugin->shouldStart());
    }
}