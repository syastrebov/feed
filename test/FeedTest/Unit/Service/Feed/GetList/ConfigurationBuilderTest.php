<?php

namespace FeedTest\Unit\Service\Feed;

use Feed\Service\Feed\Configuration\Service as ConfigurationBuilder;
use Feed\Service\Feed\Configuration\Plugin as ConfigurationPlugins;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование сервиса сборки конфигурации
 *
 * Class ConfigurationBuilderTest
 * @package FeedTest\Unit\Service\Feed
 */
class ConfigurationBuilderTest extends \PHPUnit_Framework_TestCase
{
    const DEFAULT_LIMIT = 2;

    /**
     * @var ConfigurationPlugins\Collection
     */
    private $plugins;

    /**
     * @var ConfigurationBuilder
     */
    private $builder;

    /**
     *
     */
    public function setUp()
    {
        $this->plugins = new ConfigurationPlugins\Collection();
        $this->builder = new ConfigurationBuilder($this->plugins);
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->plugins = null;
        $this->builder = null;
    }

    /**
     * Плагины должны быть пропущены
     */
    public function testShouldSkip()
    {
        $mock1 = new ConfigurationPlugins\Mock(1, true);
        $mock2 = new ConfigurationPlugins\Mock(2, false);

        $this->plugins->attach($mock1)->attach($mock2);
        $this->builder->getConfiguration(new Parameters());

        $this->assertTrue($mock1->isApplied());
        $this->assertFalse($mock2->isApplied());
    }

    /**
     * Объект конфигурации подменяется в плагине
     */
    public function testChangeConfigurationInPlugin()
    {
        $configuration = new Configuration();

        $mock1 = new ConfigurationPlugins\Mock(1, true, $configuration);
        $mock2 = new ConfigurationPlugins\Mock(2, true);

        $this->plugins->attach($mock1)->attach($mock2);
        $this->assertTrue($configuration === $this->builder->getConfiguration(new Parameters()));
    }

    /**
     * Задано максимальное количество постов
     */
    public function testLimitGteNull()
    {
        $this->assertGreaterThan(0, $this->builder->getConfiguration(new Parameters([])));
    }

    /**
     * Задать фильтр по городу
     */
    public function testSetCity()
    {
        $this->plugins->attach(new ConfigurationPlugins\City());
        $this->assertEquals(
            Configuration::create()
                ->setCity(1)
                ->setLimit(self::DEFAULT_LIMIT),
            $this->builder
                ->getConfiguration(new Parameters(['city_id' => 1]))
                ->setLimit(self::DEFAULT_LIMIT)
        );
    }

    /**
     * Задать фильтр по доходу
     */
    public function testSetMoney()
    {
        $this->plugins->attach(new ConfigurationPlugins\Money());
        $this->assertEquals(
            Configuration::create()
                ->setMoneyMin(1)
                ->setMoneyMax(3)
                ->setLimit(self::DEFAULT_LIMIT),
            $this->builder
                ->getConfiguration(new Parameters([
                    'money_min' => 1,
                    'money_max' => 3,
                ]))
                ->setLimit(self::DEFAULT_LIMIT)
        );
    }

    /**
     * Задать смещение
     */
    public function testSetOffset()
    {
        $this->plugins->attach(new ConfigurationPlugins\Offset());
        $this->assertEquals(
            Configuration::create()
                ->setOffset(1)
                ->setLimit(self::DEFAULT_LIMIT),
            $this->builder
                ->getConfiguration(new Parameters(['offset' => 1]))
                ->setLimit(self::DEFAULT_LIMIT)
        );
    }

    /**
     * Задать вкладку
     */
    public function testSetTabType()
    {
        $this->plugins->attach(new ConfigurationPlugins\TabType());
        $this->assertEquals(
            Configuration::create()
                ->setTabType(1)
                ->setLimit(self::DEFAULT_LIMIT),
            $this->builder
                ->getConfiguration(new Parameters(['type' => 1]))
                ->setLimit(self::DEFAULT_LIMIT)
        );
    }

    /**
     * Задать сортировку по полезности
     */
    public function testSetSortUseful()
    {
        $this->plugins->attach(new ConfigurationPlugins\Useful());
        $this->assertEquals(
            Configuration::create()
                ->setSortUseful(1)
                ->setLimit(self::DEFAULT_LIMIT),
            $this->builder
                ->getConfiguration(new Parameters(['orderBy' => 'useful']))
                ->setLimit(self::DEFAULT_LIMIT)
        );
    }

    /**
     * Задать тег курса
     */
    public function testSetProductTag()
    {
        $this->plugins->attach(new ConfigurationPlugins\TagProduct());
        $this->assertEquals(
            Configuration::create()
                ->setProductTags([1])
                ->setLimit(self::DEFAULT_LIMIT),
            $this->builder
                ->getConfiguration(new Parameters(['product' => 1]))
                ->setLimit(self::DEFAULT_LIMIT)
        );
    }

    /**
     * Валидация параметра / недопустимый параметр (возвращает исключение)
     *
     * @expectedException \Exception
     */
    public function testInvalidParamException()
    {
        $this->builder->validateQueryParams(new Parameters(['unknown param' => 1]));
    }

    /**
     * Валидация параметра / недопустимый параметр (возвращает false)
     */
    public function testInvalidParam()
    {
        $this->assertEquals(false, $this->builder->validateQueryParams(new Parameters(['unknown param' => 1]), false));
    }

    /**
     * Валидация параметра / недопустимый параметр (возвращает false)
     */
    public function testCheckParam()
    {
        $this->assertEquals(true, $this->builder->validateQueryParams(new Parameters(['product' => 1])));
    }
}
