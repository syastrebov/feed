<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Configuration;

use Feed\Service\Feed\Configuration\Plugin\City;
use Feed\Service\Feed\Configuration\Plugin\ConfigurationPluginInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование плагина задания конфигурации для фильтрации по городу
 *
 * Class CityTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Configuration
 */
class CityTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var City
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->plugin = new City();
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
        $this->assertEquals(ConfigurationPluginInterface::TYPE_CITY, $this->plugin->getType());
    }

    /**
     * Метод apply возвращает объект Configuration
     */
    public function testApplyReturnConfig()
    {
        $this->assertInstanceOf(
            'Feed\Service\Feed\GetList\Entity\Configuration',
            $this->plugin
                ->setQuery(new Parameters(['city_id' => 1]))
                ->apply(new Configuration())
        );
    }
}