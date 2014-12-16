<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Configuration;

use Feed\Service\Feed\Configuration\Plugin\TabType;
use Feed\Service\Feed\Configuration\Plugin\ConfigurationPluginInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование плагина задания конфигурации для выбора типа вкладки
 *
 * Class TabTypeTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Configuration
 */
class TabTypeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TabType
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->plugin = new TabType();
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->plugin = null;
    }

    /**
     * Проверка типа плагина
     */
    public function testGetType()
    {
        $this->assertEquals(ConfigurationPluginInterface::TYPE_TAB_TYPE, $this->plugin->getType());
    }

    /**
     * Метод apply возвращает объект Configuration
     */
    public function testApplyReturnConfig()
    {
        $this->assertInstanceOf(
            'Feed\Service\Feed\GetList\Entity\Configuration',
            $this->plugin
                ->setQuery(new Parameters(['type' => 1]))
                ->apply(new Configuration())
        );
    }

    /**
     * Плагин не должен запуститься
     *
     * @param array $params
     * @dataProvider providerShouldSkip
     */
    public function testShouldSkip(array $params)
    {
        $this->assertFalse($this->plugin->setQuery(new Parameters($params))->shouldStart());
    }

    /**
     * @return array
     */
    public function providerShouldSkip()
    {
        return [[['type' => 0]], [['type' => 4]], [['type' => 5]], [['type' => 6]]];
    }
}