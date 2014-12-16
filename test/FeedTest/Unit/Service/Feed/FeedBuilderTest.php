<?php

namespace FeedTest\Unit\Service;

use Feed\Service\Feed\GetList\Adapter\Mock\Insert;
use Feed\Service\Feed\GetList\Adapter\Mock\Select;
use Feed\Service\Feed\Configuration\Service as ConfigurationBuilder;
use Feed\Service\Feed\Configuration\Plugin as ConfigurationPlugin;
use Feed\Service\Feed\GetList\Entity\Configuration as EntityConfiguration;
use Feed\Service\Feed\GetList\Plugin\Filter\Collection as FilterCollection;
use Feed\Service\Feed\GetList\Plugin\Order\Collection as OrderCollection;
use Feed\Service\Feed\GetList\Tab\Collection as TabCollection;
use Feed\Service\Feed\GetList\Service as FeedService;
use Feed\Service\Feed\GetList\Entity\Collection as EntityCollection;
use Feed\Service\Feed\GetList\Tab\Tab;
use Feed\Service\Feed\Plugin\Adapter\Mock\UserHiddenPosts;
use Feed\Service\Feed\Plugin\Plugin\Filter\IDontWantToSeeThis;
use Feed\Service\Feed\Plugin\Service as FeedPluginService;
use Feed\Service\Feed\Plugin\Plugin\Content\Collection as PluginContentCollection;
use Feed\Service\Feed\Plugin\Plugin\Filter\Collection as PluginFilterCollection;
use Feed\Service\Feed\FeedBuilder;
use Feed\Entity\Feed as FeedEntity;
use Member\Entity\Member;
use Zend\EventManager\EventManager;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование сервиса сборки ленты
 *
 * Class FeedBuilderTest
 * @package FeedTest\Unit\Service
 */
class FeedBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var UserHiddenPosts
     */
    private $userHiddenPostsAdapter;

    /**
     * @var EntityCollection
     */
    private $feedCollection;

    /**
     * @var FeedBuilder
     */
    private $builder;

    /**
     *
     */
    public function setUp()
    {
        $this->feedCollection = new EntityCollection();
        $tabCollection = new TabCollection();
        $tabCollection->attach(new Tab(1, new FilterCollection(), new OrderCollection()));

        $feedService = new FeedService(
            new Select($this->feedCollection),
            new Insert($this->feedCollection),
            $tabCollection,
            new EventManager()
        );

        $member = new Member();
        $member->setId(1);

        $this->userHiddenPostsAdapter = new UserHiddenPosts();

        $pluginFilterCollection = new PluginFilterCollection();
        $pluginFilterCollection->attach(new IDontWantToSeeThis($member, $this->userHiddenPostsAdapter));

        $pluginService = new FeedPluginService($pluginFilterCollection, new PluginContentCollection());
        /** @var \Feed\Repository\Feed $feedRepository */
        $feedRepository = $this->getMock('Feed\Repository\Feed', [], [], '', false);

        $configurationCollection = new ConfigurationPlugin\Collection();
        $configurationCollection
            ->attach(new ConfigurationPlugin\Mock(0, true, EntityConfiguration::create()->setLimit(2)))
            ->attach(new ConfigurationPlugin\TabType());

        $this->builder = new FeedBuilder(
            new ConfigurationBuilder($configurationCollection),
            $feedService,
            $pluginService,
            $feedRepository
        );
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->feedCollection         = null;
        $this->userHiddenPostsAdapter = null;
        $this->builder                = null;
    }

    /**
     * Выборка в один запрос
     */
    public function testOneQueryGetList()
    {
        $this->feedCollection
            ->attach($this->getEntity(1))
            ->attach($this->getEntity(2))
            ->attach($this->getEntity(3))
            ->attach($this->getEntity(4))
            ->attach($this->getEntity(5));

        $response = $this->builder->getList(new Parameters(['type' => 1]));
        $this->assertEquals(2, $response['offset']);
    }

    /**
     * Выборка в два запроса
     */
    public function testTwoQueryGetList()
    {
        $this->feedCollection
            ->attach($this->getEntity(1))
            ->attach($this->getEntity(2))
            ->attach($this->getEntity(3))
            ->attach($this->getEntity(4))
            ->attach($this->getEntity(5));

        $this->userHiddenPostsAdapter->setFeedIds([1, 3]);

        $response = $this->builder->getList(new Parameters(['type' => 1]));
        $this->assertEquals(4, $response['offset']);
    }

    /**
     * Возвращает mock модели поста
     *
     * @param int $id
     * @return \Feed\Entity\Feed
     */
    private function getEntity($id)
    {
        $entity    = new FeedEntity();
        $refEntity = new \ReflectionClass($entity);
        $property  = $refEntity->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($entity, $id);

        $entity->setCreated(new \DateTime());

        return $entity;
    }
}