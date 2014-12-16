<?php

namespace FeedTest\Unit\Service\Feed\GetList\Entity;

use Feed\Service\Feed\GetList\Entity\Configuration;

/**
 * Лента постов / Тестирование конфигурации
 *
 * Class ConfigurationTest
 * @package FeedTest\Service\Feed\Entity
 */
class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Configuration
     */
    private $configuration;

    /**
     *
     */
    public function setUp()
    {
        $this->configuration = Configuration::create();
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->configuration = null;
    }

    /**
     * Тестирование заполнения свойств Array.<int> (успешно)
     *
     * @param array $values
     * @dataProvider providerSuccessSetIntArrayProperties
     */
    public function testSuccessSetIntArrayProperties(array $values)
    {
        $this->assertEmpty($this->configuration->getOwnerIds());
        $this->assertEquals($values, $this->configuration->setOwnerIds($values)->getOwnerIds());

        $this->assertEmpty($this->configuration->getProductTags());
        $this->assertEquals($values, $this->configuration->setProductTags($values)->getProductTags());

        $this->assertEmpty($this->configuration->getNicheTags());
        $this->assertEquals($values, $this->configuration->setNicheTags($values)->getNicheTags());
    }

    /**
     * Тестирование заполнения свойств int (успешно)
     *
     * @param int $value
     * @dataProvider providerSuccessSetIntProperty
     */
    public function testSuccessSetIntProperties($value)
    {
        $this->assertNull($this->configuration->getCity());
        $this->assertEquals($value, $this->configuration->setCity($value)->getCity());

        $this->assertNull($this->configuration->getTabType());
        $this->assertEquals($value, $this->configuration->setTabType($value)->getTabType());

        $this->assertNull($this->configuration->getMoneyMin());
        $this->assertEquals($value, $this->configuration->setMoneyMin($value)->getMoneyMin());

        $this->assertNull($this->configuration->getMoneyMax());
        $this->assertEquals($value, $this->configuration->setMoneyMax($value)->getMoneyMax());

        $this->assertNull($this->configuration->getLimit());
        $this->assertEquals($value, $this->configuration->setLimit($value)->getLimit());

        $this->assertNull($this->configuration->getOffset());
        $this->assertEquals($value, $this->configuration->setOffset($value)->getOffset());
    }

    /**
     * Тестирование заполнения свойств int нулем (успешно)
     */
    public function testSetAllowedZeroValues()
    {
        $this->assertEquals(0, $this->configuration->setOffset(0)->getOffset());
    }

    /**
     * Тестирование выставления флага сортировки по полезности
     */
    public function testUsefulCount()
    {
        $this->assertFalse($this->configuration->isSortUseful());
        $this->assertTrue($this->configuration->setSortUseful(true)->isSortUseful());
        $this->assertFalse($this->configuration->setSortUseful(false)->isSortUseful());
    }

    /**
     * Тестирование заполнения id пользователей (ошибка)
     *
     * @param array $values
     * @expectedException \Exception
     * @dataProvider providerFailureSetIntArrayProperties
     */
    public function testFailureSetOwnerIds(array $values)
    {
        $this->configuration->setOwnerIds($values);
    }

    /**
     * Тестирование заполнения тегов курса (ошибка)
     *
     * @param array $values
     * @expectedException \Exception
     * @dataProvider providerFailureSetIntArrayProperties
     */
    public function testFailureSetRouteTags(array $values)
    {
        $this->configuration->setProductTags($values);
    }

    /**
     * Тестирование заполнения тегов нишы (ошибка)
     *
     * @param array $values
     * @expectedException \Exception
     * @dataProvider providerFailureSetIntArrayProperties
     */
    public function testFailureSetNicheTags(array $values)
    {
        $this->configuration->setNicheTags($values);
    }

    /**
     * Проверка что админ
     */
    public function testIsAdmin()
    {
        $this->assertFalse($this->configuration->isAdmin());
        $this->assertTrue($this->configuration->setAsAdmin(true)->isAdmin());
        $this->assertFalse($this->configuration->setAsAdmin(false)->isAdmin());
    }

    /**
     * Тестирование заполнения свойств Array.<int> (успешно)
     *
     * @return array
     */
    public function providerSuccessSetIntArrayProperties()
    {
        return [
            [[1]],
            [[1, 2]],
        ];
    }

    /**
     * Тестирование заполнения свойств Array.<int> (ошибка)
     *
     * @return array
     */
    public function providerFailureSetIntArrayProperties()
    {
        return [
            [['1']],
            [[null]],
            [['some string']],
            [[0]],
            [[-1]],
        ];
    }

    /**
     * Тестирование заполнения свойств int (успешно)
     *
     * @return array
     */
    public function providerSuccessSetIntProperty()
    {
        return [[1], [2], [3]];
    }
}