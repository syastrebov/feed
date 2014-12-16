<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Configuration;

use Feed\Service\Feed\Configuration\Plugin\TagProduct;
use Feed\Service\Feed\Configuration\Plugin\ConfigurationPluginInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование плагина задания конфигурации для выбора курса
 *
 * Class TagProductTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Configuration
 */
class TagProductTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TagProduct
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->plugin = new TagProduct();
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
        $this->assertEquals(ConfigurationPluginInterface::TYPE_TAG_PRODUCT, $this->plugin->getType());
    }

    /**
     * Метод apply возвращает объект Configuration
     */
    public function testApplyReturnConfig()
    {
        $this->assertInstanceOf(
            'Feed\Service\Feed\GetList\Entity\Configuration',
            $this->plugin
                ->setQuery(new Parameters(['product' => 1]))
                ->apply(new Configuration())
        );
    }
}