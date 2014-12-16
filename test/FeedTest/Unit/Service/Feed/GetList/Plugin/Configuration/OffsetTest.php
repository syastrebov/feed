<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Configuration;

use Feed\Service\Feed\Configuration\Plugin\Offset;
use Feed\Service\Feed\Configuration\Plugin\ConfigurationPluginInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование плагина задания конфигурации для смещения
 *
 * Class OffsetTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Configuration
 */
class OffsetTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Offset
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->plugin = new Offset();
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
        $this->assertEquals(ConfigurationPluginInterface::TYPE_OFFSET, $this->plugin->getType());
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
}