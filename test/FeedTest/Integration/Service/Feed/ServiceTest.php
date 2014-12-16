<?php

namespace FeedTest\Integration\Service\Feed;

use Feed\Entity\Feed;
use Application\Entity;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;
use Feed\Service\Feed\GetList\Service;
use Bootstrap;
use DateTime;
use FeedTest\Integration\EnvironmentSetup;

/**
 * Лента постов / Тестирование сервиса
 *
 * Class ServiceTest
 * @package FeedTest\Integration\Service\Feed
 */
class ServiceTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Doctrine\ORM\EntityManager $entityManager
     */
    private $entityManager;

    /**
     * @var Service
     */
    private $service;

    /**
     * Создаем экземпляр окружения для тестирования
     */
    public static function setUpBeforeClass()
    {
        $environment = new EnvironmentSetup(Bootstrap::getServiceManager());
        $environment
            ->clearDoctrineCache()

            ->cloneEnvironment('bm2_social')
            ->cloneEnvironment('bm3')

            ->createMember(1, 1, 100)
            ->createMember(2, 2, 1000)

            ->createTag(1, Entity\Tag::TYPE_FEED_PRODUCT, 'Курс 1')
            ->createTag(2, Entity\Tag::TYPE_FEED_PRODUCT, 'Курс 2')
            ->createTag(3, Entity\Tag::TYPE_FEED_PRODUCT, 'Курс 3')

            ->createTag(4, Entity\Tag::TYPE_FEED_NICHE, 'Ниша 1')
            ->createTag(5, Entity\Tag::TYPE_FEED_NICHE, 'Ниша 2')
            ->createTag(6, Entity\Tag::TYPE_FEED_NICHE, 'Ниша 3')

            ->overrideEntityManagerConnection();
    }

    /**
     * Очищаем экземпляр окружения
     */
//    public static function tearDownAfterClass()
//    {
//        $environment = new EnvironmentSetup(Bootstrap::getServiceManager());
//        $environment
//            ->clearTestEnvironment('bm2_social')
//            ->clearTestEnvironment('bm3')
//            ->clearDoctrineCache();
//    }

    /**
     *
     */
    public function setUp()
    {
        $this->entityManager = Bootstrap::getServiceManager()->get('Doctrine\ORM\EntityManager');
        $this->service       = Bootstrap::getServiceManager()->get('FeedService');
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->entityManager = null;
        $this->service       = null;
    }

    /**
     * Получение объекта сервиса
     */
    public function testGetInstance()
    {
        $this->assertInstanceOf('Feed\Service\Feed\GetList\Service', $this->service);
    }

    /**
     * Добавление постов в базу
     */
    public function testInsert()
    {
        /** @var \Application\Repository\Tag $tagRepository */
        $tagRepository = $this->entityManager->getRepository('Application\Entity\Tag');
        /** @var \Member\Repository\Member $memberRepository */
        $memberRepository = $this->entityManager->getRepository('Member\Entity\Member');

        $entity1 = new Feed();
        $entity1
            ->setTitle('Title aaa')
            ->setText('Text aaa')
            ->setMember($memberRepository->getById(1))
            ->setAccess(1)
            ->setTypeId(1)
            ->setHelpfulCount(5)
            ->addTag($tagRepository->getById(1))
            ->setCreated(new DateTime())
            ->setUpdated(new DateTime('+1 hour'));

        $entity2 = new Feed();
        $entity2
            ->setTitle('Title bbb')
            ->setText('Text bbb')
            ->setMember($memberRepository->getById(2))
            ->setAccess(1)
            ->setTypeId(1)
            ->setHelpfulCount(1)
            ->addTag($tagRepository->getById(1))
            ->addTag($tagRepository->getById(4))
            ->setCreated(new DateTime('+5 second'))
            ->setUpdated(new DateTime('+5 hour'));

        $entity3 = new Feed();
        $entity3
            ->setTitle('Title ccc')
            ->setText('Text ccc')
            ->setMember($memberRepository->getById(2))
            ->setAccess(1)
            ->setTypeId(1)
            ->setHelpfulCount(10)
            ->addTag($tagRepository->getById(3))
            ->addTag($tagRepository->getById(6))
            ->setCreated(new DateTime('+10 second'))
            ->setUpdated(new DateTime('+5 hour'));

        $this->service->insert($entity1);
        $this->service->insert($entity2);
        $this->service->insert($entity3);
    }

    /**
     * Получение списка из вкладки реки без фильтров
     */
    public function testTabAllWithNoFilter()
    {
        $this->assertEquals(3, $this->service
            ->getList(Configuration::create()->setTabType(FeedTabInterface::TYPE_ALL))
            ->getCollection()
            ->count()
        );
    }

    /**
     * Получение списка из вкладки реки с фильтром по городу
     */
    public function testTabAllFilterCity()
    {
        $this->assertEquals(1, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setCity(1)
            )
            ->getCollection()
            ->count()
        );
        $this->assertEquals(2, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setCity(2)
            )
            ->getCollection()
            ->count()
        );
    }

    /**
     * Получение списка из вкладки мои посты с фильтром по владельцу
     */
    public function testTabMyFilterOwner()
    {
        $this->assertEquals(1, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_MY)
                    ->setOwnerIds([1])
            )
            ->getCollection()
            ->count()
        );
        $this->assertEquals(3, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_MY)
                    ->setOwnerIds([1, 2])
            )
            ->getCollection()
            ->count()
        );
    }

    /**
     * Получение списка из вкладки реки с фильтром по тегам
     */
    public function testFilterTag()
    {
        $this->assertEquals(2, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setProductTags([1, 2])
            )
            ->getCollection()
            ->count()
        );
        $this->assertEquals(1, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setProductTags([1, 2])
                    ->setNicheTags([4, 5])
            )
            ->getCollection()
            ->count()
        );
    }

    /**
     * Получение списка с фильтром по доходу
     */
    public function testFilterMoney()
    {
        // Подходят оба пользователя (у них 3 поста)
        $this->assertEquals(3, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setMoneyMin(10)
            )
            ->getCollection()
            ->count()
        );
        // Подходит оба пользователя
        $this->assertEquals(3, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setMoneyMin(100)
            )
            ->getCollection()
            ->count()
        );
        // Подходит только первый пользователь (у него 1 пост)
        $this->assertEquals(1, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setMoneyMin(10)
                    ->setMoneyMax(100)
            )
            ->getCollection()
            ->count()
        );
        // Подходит только второй пользователь (у него 2 поста)
        $this->assertEquals(2, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setMoneyMin(150)
                    ->setMoneyMax(1500)
            )
            ->getCollection()
            ->count()
        );
    }

    /**
     * Получение списка с базовой сортировкой по дате
     */
    public function testOrderByDate()
    {
        $result = $this->service
            ->getList(Configuration::create()->setTabType(FeedTabInterface::TYPE_ALL))
            ->getCollection()
            ->toArray();

        $this->assertEquals(3, count($result));

        $this->assertNotNull($result[0]);
        $this->assertNotNull($result[1]);
        $this->assertNotNull($result[2]);

        /** @var Feed $resultEntity1 */
        /** @var Feed $resultEntity2 */
        /** @var Feed $resultEntity3 */

        $resultEntity1 = $result[0];
        $resultEntity2 = $result[1];
        $resultEntity3 = $result[2];

        $this->assertEquals('Title ccc', $resultEntity1->getTitle());
        $this->assertEquals('Title bbb', $resultEntity2->getTitle());
        $this->assertEquals('Title aaa', $resultEntity3->getTitle());
    }

    /**
     * Получение списка с сортировкой по полезности
     */
    public function testOrderByUseful()
    {
        $result = $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setSortUseful(true)
            )
            ->getCollection()
            ->toArray();

        $this->assertEquals(3, count($result));

        /** @var Feed $resultEntity1 */
        /** @var Feed $resultEntity2 */
        /** @var Feed $resultEntity3 */

        $resultEntity1 = $result[0];
        $resultEntity2 = $result[1];
        $resultEntity3 = $result[2];

        $this->assertEquals('Title ccc', $resultEntity1->getTitle());
        $this->assertEquals('Title aaa', $resultEntity2->getTitle());
        $this->assertEquals('Title bbb', $resultEntity3->getTitle());
    }

    /**
     * Получение списка с несколькими фильтрами
     */
    public function testComplexFilters()
    {
        $this->assertEquals(1, $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setMoneyMin(1)
                    ->setMoneyMax(100000)
                    ->setCity(2)
                    ->setProductTags([1, 2])
                    ->setNicheTags([4, 5])
                    ->setSortUseful(true)
            )
            ->getCollection()
            ->count()
        );
    }

    /**
     * Получение списка с ограничением и смещением
     */
    public function testLimitOffset()
    {
        $result = $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setLimit(1)
                    ->setOffset(0)
            )
            ->getCollection()
            ->toArray();

        $this->assertEquals(1, count($result));

        /** @var Feed $resultEntity1 */
        $resultEntity1 = $result[0];
        $this->assertEquals('Title ccc', $resultEntity1->getTitle());

        $result = $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setLimit(1)
                    ->setOffset(1)
            )
            ->getCollection()
            ->toArray();

        $this->assertEquals(1, count($result));

        /** @var Feed $resultEntity1 */
        $resultEntity1 = $result[0];
        $this->assertEquals('Title bbb', $resultEntity1->getTitle());

        $result = $this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setLimit(1)
                    ->setOffset(2)
            )
            ->getCollection()
            ->toArray();

        $this->assertEquals(1, count($result));

        /** @var Feed $resultEntity1 */
        $resultEntity1 = $result[0];
        $this->assertEquals('Title aaa', $resultEntity1->getTitle());

        $this->assertEmpty($this->service
            ->getList(
                Configuration::create()
                    ->setTabType(FeedTabInterface::TYPE_ALL)
                    ->setLimit(1)
                    ->setOffset(3)
            )
            ->getCollection()
            ->toArray()
        );
    }
}
