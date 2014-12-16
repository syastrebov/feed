<?php

namespace FeedTest\Integration\Controller;

use FeedTest\Integration\EnvironmentSetup;
use Bootstrap;
use Zend\Http\Request;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование restful контроллера
 *
 * Class RestTest
 * @package FeedTest\Integration\Controller
 */
class RestTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \Feed\Controller\Rest
     */
    private $controller;

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

            ->createMember(1, 1, 1)
            ->overrideEntityManagerConnection()
            ->clearDoctrineCache();
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
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = Bootstrap::getServiceManager()->get('Doctrine\ORM\EntityManager');
        /** @var \Member\Repository\Member $memberRepository */
        $memberRepository = $entityManager->getRepository('Member\Entity\Member');

        $controller = $this->getMock('Feed\Controller\Rest', ['getMember']);
        $controller
            ->expects($this->any())
            ->method('getMember')
            ->will($this->returnValue($memberRepository->getById(1)));

        /** @var \Feed\Controller\Rest $controller */
        $controller->setServiceLocator(Bootstrap::getServiceManager());
        $this->controller = $controller;
    }

    /**
     *
     */
    public function tearDown()
    {
        $this->controller = null;
    }

    /**
     * Получение объекта авторизации
     */
    public function testGetMember()
    {
        $this->assertInstanceOf('Member\Entity\Member', $this->controller->getMember());
    }

    /**
     * Добавление записи в ленту (успешно)
     */
    public function testCreate()
    {
        $response = $this->controller->create([
            'text' => 'some text',
        ]);

        $this->assertInternalType('array', $response);
        $this->assertNotEmpty($response);
    }

    /**
     * Добавление записи в ленту (не передан текст поста)
     *
     * @expectedException \Exception
     */
    public function testCreateNoContent()
    {
        $this->controller->create([]);
    }

    /**
     * Получение списка неавторизованным пользователем без параметров (не указан тип списка)
     *
     * @expectedException \Exception
     */
    public function testGetListNotAuthorizedWithoutQueryParamsNoTab()
    {
        $response = $this->controller->getList();
        $this->assertInternalType('array', $response);
    }

    /**
     * Передан неправильный тип запроса
     *
     * @expectedException \Feed\Exception\NotFoundException
     */
    public function testGetListNotHttpRequest()
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = Bootstrap::getServiceManager()->get('Doctrine\ORM\EntityManager');
        /** @var \Member\Repository\Member $memberRepository */
        $memberRepository = $entityManager->getRepository('Member\Entity\Member');

        $request = new \Zend\Console\Request();

        $controller = $this->getMock('Feed\Controller\Rest', ['getRequest', 'getMember']);
        $controller
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));
        $controller
            ->expects($this->any())
            ->method('getMember')
            ->will($this->returnValue($memberRepository->getById(1)));

        /** @var \Feed\Controller\Rest $controller */
        $this->controller = $controller;
        $this->controller->setServiceLocator(Bootstrap::getServiceManager());
        $this->controller->getList();
    }

    /**
     * Получение списка неавторизованным пользователем без параметров
     */
    public function testGetListNotAuthorizedWithoutQueryParams()
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = Bootstrap::getServiceManager()->get('Doctrine\ORM\EntityManager');
        /** @var \Member\Repository\Member $memberRepository */
        $memberRepository = $entityManager->getRepository('Member\Entity\Member');

        $request = new Request() ;
        $request->setQuery(new Parameters(['type' => 1]));

        $controller = $this->getMock('Feed\Controller\Rest', ['getRequest', 'getMember']);
        $controller
            ->expects($this->any())
            ->method('getRequest')
            ->will($this->returnValue($request));
        $controller
            ->expects($this->any())
            ->method('getMember')
            ->will($this->returnValue($memberRepository->getById(1)));

        /** @var \Feed\Controller\Rest $controller */
        $this->controller = $controller;
        $this->controller->setServiceLocator(Bootstrap::getServiceManager());

        $this->assertInternalType('array', $this->controller->getList());
    }
}