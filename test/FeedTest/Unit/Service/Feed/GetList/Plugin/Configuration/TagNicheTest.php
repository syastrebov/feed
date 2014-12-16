<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Configuration;

use Doctrine\Common\Collections\ArrayCollection;
use Feed\Service\Feed\Configuration\Plugin\TagNiche;
use Feed\Service\Feed\Configuration\Plugin\ConfigurationPluginInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;
use Application\Entity\Tag as TagEntity;
use Member\Entity\Member;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование плагина задания конфигурации для выбора нишы (интереса)
 *
 * Class TagNicheTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Configuration
 */
class TagNicheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Member
     */
    private $identity;

    /**
     * @var TagNiche
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->identity = new Member();
        $this->plugin   = new TagNiche($this->identity);
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->identity = null;
        $this->plugin   = null;
    }

    /**
     * Проверка типа плагина
     */
    public function testGetType()
    {
        $this->assertEquals(ConfigurationPluginInterface::TYPE_TAG_NICHE, $this->plugin->getType());
    }

    /**
     * Метод apply возвращает объект Configuration
     */
    public function testApplyReturnConfig()
    {
        $this->assertInstanceOf(
            'Feed\Service\Feed\GetList\Entity\Configuration',
            $this->plugin
                ->setQuery(new Parameters(['route' => 1]))
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
     * Плагин применится из строки запроса
     */
    public function testApplyQuery()
    {
        $this->assertEquals(
            Configuration::create()->setNicheTags([1]),
            $this->plugin
                ->setQuery(new Parameters(['niche' => 1]))
                ->apply(new Configuration())
        );
    }

    /**
     * Плагин применится для неавторизованного пользователя
     */
    public function testApplyNotAuthorized()
    {
        $this->assertEquals(
            new Configuration(),
            $this->plugin
                ->setQuery(new Parameters())
                ->apply(new Configuration())
        );
    }

    /**
     * Плагин применится для авторизованного пользователя (передан другой memberId в queryParams)
     */
    public function testApplyAuthorizedDifferentExternalMemberId()
    {
        $this->assertEquals(
            new Configuration(),
            $this->plugin
                ->setQuery(new Parameters(['user_id' => 2]))
                ->apply(new Configuration())
        );
    }

    /**
     * Плагин применится для авторизованного пользователя (нет тегов)
     */
    public function testApplyNoTags()
    {
        $this->identity->setId(1);
        $this->assertEquals(
            new Configuration(),
            $this->plugin
                ->setQuery(new Parameters(['user_id' => 1]))
                ->apply(new Configuration())
        );
    }

    /**
     * Плагин применится для авторизованного пользователя (есть теги)
     */
    public function testApplyHasTags()
    {
        $tags = new ArrayCollection();
        $tags->add($this->getTag(2, TagEntity::TYPE_FEED_NICHE));
        $tags->add($this->getTag(3, TagEntity::TYPE_FEED_PRODUCT));
        $tags->add($this->getTag(4, TagEntity::TYPE_FEED_NICHE));

        $identity = $this->getMock('\Member\Entity\Member', ['getTags']);
        $identity
            ->expects($this->any())
            ->method('getTags')
            ->will($this->returnValue($tags));

        /** @var \Member\Entity\Member $identity */
        $this->plugin = new TagNiche($identity->setId(1));
        $this->assertEquals(
            Configuration::create()->setNicheTags([2, 4]),
            $this->plugin
                ->setQuery(new Parameters())
                ->apply(new Configuration())
        );
    }

    /**
     * Плагин не должен запуститься
     *
     * @return array
     */
    public function providerShouldSkip()
    {
        return [
            [['type' => 0]],
            [['type' => FeedTabInterface::TYPE_MY]],
            [['type' => FeedTabInterface::TYPE_PROFILE]],
            [['type' => 4]],
            [['type' => 5]],
            [['type' => 6]]
        ];
    }

    /**
     * Возвращает mock модели тега
     *
     * @param int $id
     * @param int $typeId
     *
     * @return \Feed\Entity\Feed
     */
    private function getTag($id, $typeId)
    {
        $entity    = new TagEntity();
        $refEntity = new \ReflectionClass($entity);
        $property  = $refEntity->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, $id);

        $entity->setTypeId($typeId);
        return $entity;
    }
}