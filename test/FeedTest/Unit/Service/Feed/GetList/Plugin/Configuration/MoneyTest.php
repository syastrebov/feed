<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Configuration;

use Feed\Service\Feed\Configuration\Plugin\Money;
use Feed\Service\Feed\Configuration\Plugin\ConfigurationPluginInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование плагина задания конфигурации для фильтрации по доходу
 *
 * Class MoneyTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Configuration
 */
class MoneyTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Money
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->plugin = new Money();
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
        $this->assertEquals(ConfigurationPluginInterface::TYPE_MONEY, $this->plugin->getType());
    }

    /**
     * Метод apply возвращает объект Configuration
     */
    public function testApplyReturnConfig()
    {
        $this->assertInstanceOf(
            'Feed\Service\Feed\GetList\Entity\Configuration',
            $this->plugin
                ->setQuery(new Parameters())
                ->apply(new Configuration())
        );
    }

    /**
     * Плагин должен запуститься
     *
     * @param array $params
     * @dataProvider providerShouldStart
     */
    public function testShouldStart(array $params)
    {
        $this->assertTrue($this->plugin->setQuery(new Parameters($params))->shouldStart());
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
     * Плагин должен запуститься
     *
     * @return array
     */
    public function providerShouldStart()
    {
        return [
            [['money_min' => 1]],
            [['money_max' => 1]],
            [['money_min' => 1, 'money_max' => 2]],
        ];
    }

    /**
     * Плагин не должен запуститься
     *
     * @return array
     */
    public function providerShouldSkip()
    {
        return [
            [[]],
            [['money_min' => 0]],
            [['money_min' => 'a']],
            [['money_max' => 0]],
            [['money_max' => 'a']],
            [['money_min' => 0, 'money_max' => 0]],
            [['money_min' => 100, 'money_max' => 10]],
        ];
    }
}