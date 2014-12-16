<?php

namespace FeedTest\Unit\Service\Feed\Plugin\Configuration;

use Doctrine\Common\Collections\ArrayCollection;
use Feed\Service\Feed\Configuration\Plugin\Owner;
use Feed\Service\Feed\Configuration\Plugin\ConfigurationPluginInterface;
use Feed\Service\Feed\Configuration\Adapter\Mock\Subscriber as MemberAdapter;
use Feed\Service\Feed\Plugin\Adapter\Mock\User as UserAdapter;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;
use Member\Entity\Member;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Тестирование плагина задания конфигурации для выбора списка пользователей
 *
 * Class OwnerTest
 * @package FeedTest\Unit\Service\Feed\Plugin\Configuration
 */
class OwnerTest extends \PHPUnit_Framework_TestCase
{
    const DEFAULT_LIMIT = 2;

    /**
     * @var Member
     */
    private $identity;

    /**
     * @var Owner
     */
    public $plugin;

    /**
     *
     */
    public function setUp()
    {
        $this->identity = new Member();
        $this->plugin   = new Owner($this->identity, new UserAdapter(), new MemberAdapter());
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
        $this->assertEquals(ConfigurationPluginInterface::TYPE_OWNER, $this->plugin->getType());
    }

    /**
     * Метод apply возвращает объект Configuration
     */
    public function testApplyReturnConfig()
    {
        $this->identity->setId(1);
        $this->assertInstanceOf(
            'Feed\Service\Feed\GetList\Entity\Configuration',
            $this->plugin
                ->setQuery(new Parameters(['type' => 3]))
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
     * Вкладка профиль без userId в queryParams (не авторизован)
     *
     * @expectedException \Feed\Exception\NotFoundException
     */
    public function testApplyProfileTabWithoutExternalMemberIdNotAuthorized()
    {
        $this->plugin
            ->setQuery(new Parameters(['type' => FeedTabInterface::TYPE_PROFILE]))
            ->apply(new Configuration());
    }

    /**
     * Вкладка профиль без userId в queryParams (авторизован)
     */
    public function testApplyProfileTabWithoutExternalMemberId()
    {
        $this->identity->setId(1);
        $this->assertEquals(
            Configuration::create()->setOwnerIds([1]),
            $this->plugin
                ->setQuery(new Parameters(['type' => FeedTabInterface::TYPE_PROFILE]))
                ->apply(new Configuration())
        );
    }

    /**
     * Вкладка профиль с userId = identityMemberId в queryParams
     */
    public function testApplyProfileTabOwnExternalMemberId()
    {
        $this->identity->setId(1);
        $this->assertEquals(
            Configuration::create()->setOwnerIds([1]),
            $this->plugin
                ->setQuery(new Parameters([
                    'type'    => FeedTabInterface::TYPE_PROFILE,
                    'user_id' => 1,
                ]))
                ->apply(new Configuration())
        );
    }

    /**
     * Вкладка профиль с userId != identityMemberId в queryParams (пользователь не найден)
     *
     * @expectedException \Feed\Exception\NotFoundException
     */
    public function testApplyProfileTabForeignExternalMemberIdNotFound()
    {
        $this->assertEquals(
            Configuration::create()->setOwnerIds([3]),
            $this->plugin
                ->setQuery(new Parameters([
                    'type'    => FeedTabInterface::TYPE_PROFILE,
                    'user_id' => 3,
                ]))
                ->apply(new Configuration())
        );
    }

    /**
     * Вкладка профиль с userId != identityMemberId в queryParams
     */
    public function testApplyProfileTabForeignExternalMemberId()
    {
        $customMember = new Member();
        $customMember->setId(3);

        $this->plugin = new Owner($this->identity, new UserAdapter($customMember), new MemberAdapter());
        $this->assertEquals(
            Configuration::create()->setOwnerIds([3]),
            $this->plugin
                ->setQuery(new Parameters([
                    'type'    => FeedTabInterface::TYPE_PROFILE,
                    'user_id' => 3,
                ]))
                ->apply(new Configuration())
        );
    }

    /**
     * Вкладка поток (не авторизован)
     *
     * @expectedException \Feed\Exception\NotFoundException
     */
    public function testApplyMyTabNotAuthorized()
    {
        $this->plugin
            ->setQuery(new Parameters(['type' => FeedTabInterface::TYPE_MY]))
            ->apply(new Configuration());
    }

    /**
     * Вкладка поток (без подписчиков)
     */
    public function testApplyMyTabNoFavourites()
    {
        $this->identity->setId(1);
        $this->assertEquals(
            Configuration::create()
                ->setOwnerIds([1])
                ->setLimit(self::DEFAULT_LIMIT),
            $this->plugin
                ->setQuery(new Parameters(['type' => FeedTabInterface::TYPE_MY]))
                ->apply(Configuration::create()->setLimit(self::DEFAULT_LIMIT))
        );
    }

    /**
     * Вкладка поток (с подписчиками)
     */
    public function testApplyMyTabWithFavourites()
    {
        $favourite1 = $this->getMock('\Member\Entity\Subscription');
        $favourite1
            ->expects($this->any())
            ->method('getTargetId')
            ->will($this->returnValue(2));

        $favourite2 = $this->getMock('\Member\Entity\Subscription');
        $favourite2
            ->expects($this->any())
            ->method('getTargetId')
            ->will($this->returnValue(3));

        $favourites = new ArrayCollection();
        $favourites->add($favourite1);
        $favourites->add($favourite2);

        $identity = $this->getMock('\Member\Entity\Member', ['getSubscribers']);
        $identity
            ->expects($this->any())
            ->method('getSubscribers')
            ->will($this->returnValue($favourites));

        /** @var \Member\Entity\Member $identity */
        $this->plugin = new Owner($identity->setId(1), new UserAdapter(), new MemberAdapter($favourites));
        $this->assertEquals(
            Configuration::create()->setOwnerIds([1, 2, 3]),
            $this->plugin
                ->setQuery(new Parameters(['type' => FeedTabInterface::TYPE_MY]))
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
        return [[['type' => 0]], [['type' => 1]], [['type' => 4]], [['type' => 5]], [['type' => 6]]];
    }
}
