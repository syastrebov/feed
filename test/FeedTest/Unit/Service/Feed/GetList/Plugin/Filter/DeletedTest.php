<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Filter;

use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Plugin\Filter\Deleted;
use Feed\Service\Feed\GetList\Adapter\Mock\Adapter as MockAdapter;
use Feed\Service\Feed\GetList\Plugin\Filter\FeedFilterInterface;

/**
 * Лента постов / Тестирование фильтра скрывать удаленные посты
 *
 * Class DeletedTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Filter
 */
class DeletedTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $defaultConfig;

    /**
     * @var Deleted
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->defaultConfig = new Configuration();
        $this->plugin        = new Deleted(new MockAdapter(), $this->defaultConfig);
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
        $this->assertEquals(FeedFilterInterface::TYPE_IS_DELETED, $this->plugin->getType());
    }

    /**
     * Плагин должен запуститься
     */
    public function testShouldStart()
    {
        $configuration = new Configuration();
        $configuration->setAsAdmin(true);

        $this->assertFalse($this->plugin->shouldStart());
        $this->assertTrue($this->plugin->setConfiguration($configuration)->shouldStart());
    }

    /**
     * Клонирование конфигурации при передаче через метод
     */
    public function testCloneSetConfiguration()
    {
        $configuration = new Configuration();
        $this->plugin->setConfiguration($configuration);

        $configuration->setAsAdmin(true);
        $this->assertFalse($this->plugin->shouldStart());
    }

    /**
     * Клонирование конфигурации при передаче через конструктор
     */
    public function testConstructCloneConfiguration()
    {
        $this->defaultConfig->setAsAdmin(true);
        $this->assertFalse($this->plugin->shouldStart());
    }
}