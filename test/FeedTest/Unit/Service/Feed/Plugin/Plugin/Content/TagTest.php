<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Plugin\Content;

use Feed\Entity\Feed as FeedEntity;
use Feed\Service\Feed\GetList\Entity\Collection;
use Feed\Service\Feed\Plugin\Entity\FeedTag;
use Feed\Service\Feed\Plugin\Entity\FeedTagCollection;
use Feed\Service\Feed\Plugin\Plugin\Content\FeedContentInterface;
use Feed\Service\Feed\Plugin\Plugin\Content\Tag as TagPlugin;
use Feed\Service\Feed\Plugin\Adapter\Mock\Tag as Adapter;
use Application\Entity\Tag as TagEntity;

/**
 * Лента постов / Тестирование плагина контента получения тегов
 *
 * Class TagTest
 * @package FeedTest\Unit\Service\FeedPlugin\Plugin\Content
 */
class TagTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Adapter
     */
    private $adapter;

    /**
     * @var TagPlugin
     */
    private $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->adapter = new Adapter();
        $this->plugin  = new TagPlugin($this->adapter);
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->adapter = null;
        $this->plugin  = null;
    }

    /**
     * Тип плагина
     */
    public function testGetType()
    {
        $this->assertEquals(FeedContentInterface::TYPE_TAG, $this->plugin->getType());
    }

    /**
     * Применить плагин к коллекции
     */
    public function testApplyCollection()
    {
        $feeds = new Collection();
        $tags  = new FeedTagCollection();

        $tags->attach(new FeedTag(1, $this->getTag(1, 1)));
        $tags->attach(new FeedTag(2, $this->getTag(2, 1)));
        $tags->attach(new FeedTag(1, $this->getTag(3, 1)));

        $feed1 = $this->getFeed(1);
        $feed2 = $this->getFeed(2);

        $feeds->attach($feed1);
        $feeds->attach($feed2);

        $this->adapter->setFeedTags($tags);
        $this->plugin->apply($feeds);

        $this->assertEquals(2, count($feed1->getTags()));
        $this->assertEquals(1, count($feed2->getTags()));
    }

    /**
     * Возвращает mock модели тега
     *
     * @param int $id
     * @param int $typeId
     *
     * @return TagEntity
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

    /**
     * Возвращает mock модели поста
     *
     * @param int $id
     * @return FeedEntity
     */
    private function getFeed($id)
    {
        $entity    = new FeedEntity();
        $refEntity = new \ReflectionClass($entity);

        $property  = $refEntity->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, $id);

        return $entity;
    }
}