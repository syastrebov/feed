<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Filter;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Plugin\Filter\Siberia;
use Feed\Service\Feed\GetList\Adapter\Mock\Adapter as MockAdapter;
use Feed\Service\Feed\GetList\Plugin\Filter\FeedFilterInterface;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;

/**
 * Лента постов / Тестирование фильтра отправки в сибирь
 *
 * Class SiberiaTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Filter
 */
class SiberiaTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $defaultConfig;

    /**
     * @var Siberia
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->defaultConfig = new Configuration();
        $this->plugin        = new Siberia(new MockAdapter(), $this->defaultConfig);
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
        $this->assertEquals(FeedFilterInterface::TYPE_IS_SIBERIA, $this->plugin->getType());
    }

    /**
     * Плагин должен запуститься
     */
    public function testShouldStart()
    {
        $this->assertTrue($this->plugin->shouldStart());
        $this->assertTrue($this->plugin->setConfiguration(
            Configuration::create()->setTabType(FeedTabInterface::TYPE_ALL)
        )->shouldStart());
        $this->assertTrue($this->plugin->setConfiguration(
            Configuration::create()->setTabType(FeedTabInterface::TYPE_MY)
        )->shouldStart());
        $this->assertFalse($this->plugin->setConfiguration(
            Configuration::create()->setTabType(FeedTabInterface::TYPE_PROFILE)
        )->shouldStart());
    }

    /**
     * Клонирование конфигурации при передаче через метод
     */
    public function testCloneSetConfiguration()
    {
        $configuration = new Configuration();
        $this->plugin->setConfiguration($configuration);

        $configuration->setTabType(FeedTabInterface::TYPE_PROFILE);
        $this->assertTrue($this->plugin->shouldStart());
    }

    /**
     * Клонирование конфигурации при передаче через конструктор
     */
    public function testConstructCloneConfiguration()
    {
        $this->defaultConfig->setTabType(FeedTabInterface::TYPE_PROFILE);
        $this->assertTrue($this->plugin->shouldStart());
    }
}