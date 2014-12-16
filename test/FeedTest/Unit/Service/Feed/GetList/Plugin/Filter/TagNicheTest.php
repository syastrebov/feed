<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Filter;

use Feed\Service\Feed\GetList\Plugin\Filter\TagNiche;
use Feed\Service\Feed\GetList\Adapter\Mock\Adapter as MockAdapter;
use Feed\Service\Feed\GetList\Plugin\Filter\FeedFilterInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Тестирование фильтра тегов выбора нишы
 *
 * Class TagNicheTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Filter
 */
class TagNicheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $defaultConfig;

    /**
     * @var TagNiche
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->defaultConfig = new Configuration();
        $this->plugin        = new TagNiche(new MockAdapter(), $this->defaultConfig);
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
        $this->assertEquals(FeedFilterInterface::TYPE_TAG_NICHE, $this->plugin->getType());
    }

    /**
     * Плагин должен запуститься
     */
    public function testShouldStart()
    {
        $configuration = new Configuration();
        $configuration->setNicheTags([1]);

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

        $configuration->setNicheTags([1]);
        $this->assertFalse($this->plugin->shouldStart());
    }

    /**
     * Клонирование конфигурации при передаче через конструктор
     */
    public function testConstructCloneConfiguration()
    {
        $this->defaultConfig->setNicheTags([1]);
        $this->assertFalse($this->plugin->shouldStart());
    }
}