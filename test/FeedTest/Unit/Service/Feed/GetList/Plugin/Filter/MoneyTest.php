<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\Filter\Money;
use Feed\Service\Feed\GetList\Adapter\Mock\Adapter as MockAdapter;
use Feed\Service\Feed\GetList\Plugin\Filter\FeedFilterInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Тестирование фильтра дохода
 *
 * Class MoneyTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Filter
 */
class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Money
     */
    private $plugin;

    /**
     * @var Configuration
     */
    private $defaultConfig;

    /**
     *
     */
    public function setUp()
    {
        $this->defaultConfig = new Configuration();
        $this->plugin        = new Money(new MockAdapter(), $this->defaultConfig);
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
        $this->assertEquals(FeedFilterInterface::TYPE_MONEY, $this->plugin->getType());
    }

    /**
     * Плагин должен запуститься
     */
    public function testShouldStart()
    {
        $configuration = new Configuration();
        $configuration->setMoneyMin(1);

        $this->assertFalse($this->plugin->shouldStart());
        $this->assertTrue($this->plugin->setConfiguration($configuration)->shouldStart());

        $configuration = new Configuration();
        $this->assertFalse($this->plugin->setConfiguration($configuration)->shouldStart());

        $configuration = new Configuration();
        $configuration->setMoneyMax(1);

        $this->assertTrue($this->plugin->setConfiguration($configuration)->shouldStart());

        $configuration = new Configuration();
        $configuration->setMoneyMin(1);
        $configuration->setMoneyMax(1);

        $this->assertTrue($this->plugin->setConfiguration($configuration)->shouldStart());
    }

    /**
     * Клонирование конфигурации при передаче через метод
     */
    public function testCloneSetConfiguration()
    {
        $configuration = new Configuration();
        $this->plugin->setConfiguration($configuration);

        $configuration->setMoneyMin(1);
        $this->assertFalse($this->plugin->shouldStart());
    }

    /**
     * Клонирование конфигурации при передаче через конструктор
     */
    public function testConstructCloneConfiguration()
    {
        $this->defaultConfig->setMoneyMin(1);
        $this->assertFalse($this->plugin->shouldStart());
    }
}