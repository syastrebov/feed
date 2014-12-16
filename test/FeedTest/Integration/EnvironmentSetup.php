<?php

namespace FeedTest\Integration;

use Doctrine\ORM\EntityManager;
use Member\Entity\SubscriptionGroup;
use Zend\ServiceManager\ServiceManager;
use ZendDbMigrations\Library\IntegrationTestMigration;
use Member\Security\Service as SecurityService;

/**
 * Лента постов / Базовый класс для интеграционных тестов
 *
 * Class AbstractIntegrationTest
 * @package FeedTest\Integration
 */
class EnvironmentSetup
{
    // Созданны ли таблицы и накатаны новые
    protected static $tableCreated = [];

    /**
     * Constructor
     *
     * @param ServiceManager $serviceManager
     */
    public function __construct(ServiceManager $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * Создать копию окружения (склонировать структуру схемы БД)
     *
     * @param string $originalSchemaName
     * @return $this
     */
    public function cloneEnvironment($originalSchemaName)
    {
        /** @var IntegrationTestMigration $integrationService */
        $integrationService = $this->serviceManager->get('IntegrationTestMigrationService');

        if (isset(self::$tableCreated[$originalSchemaName]) && self::$tableCreated[$originalSchemaName]) {
            $integrationService->setSchema($originalSchemaName)->clearTables();
        } else {
            $integrationService->setSchema($originalSchemaName)->dropTables()->createTables();
            self::$tableCreated[$originalSchemaName] = true;
        }

        return $this;
    }

    /**
     * Очистить копию окружения (удалить таблицы в копии схемы БД)
     *
     * @param string $originalSchemaName
     * @return $this
     */
    public function clearTestEnvironment($originalSchemaName)
    {
        /** @var IntegrationTestMigration $integrationService */
        $integrationService = $this->serviceManager->get('IntegrationTestMigrationService');
        $integrationService->setSchema($originalSchemaName)->dropTables();

        if (isset(self::$tableCreated[$originalSchemaName])) {
            self::$tableCreated[$originalSchemaName] = false;
        }

        return $this;
    }

    /**
     * Подменить подключение к БД
     *
     * @return $this
     */
    public function overrideEntityManagerConnection()
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->serviceManager->get('doctrine.entitymanager.orm_default_test');
        $this->serviceManager
            ->setAllowOverride(true)
            ->setAlias('Doctrine\ORM\EntityManager', 'doctrine.entitymanager.orm_default_test');

        $entityManager
            ->getClassMetadata('Member\Entity\Profile')
            ->setPrimaryTable(['name' => 'bm3_test.user_profile']);

        return $this;
    }

    /**
     * Очистить кеш доктрины
     *
     * @return $this
     */
    public function clearDoctrineCache()
    {
        $this->getEntityManager()->clear();

        return $this;
    }

    /**
     * Добавить пользователя
     *
     * @param int $id
     * @param int $cityId
     * @param int $profit
     *
     * @return $this
     */
    public function createMember($id, $cityId, $profit)
    {
        $this->overrideEntityManagerConnection();

        $this->getEntityManager()
             ->getConnection()
             ->prepare('INSERT INTO bm3_test.user_profile (user_id, profit) VALUES(?, ?)')
             ->execute([$id, $profit]);
        $this->getEntityManager()
            ->getConnection()
            ->prepare('INSERT INTO bm2_social_test.subscription_group (member_id, name, type) VALUES(?, ?, ?)')
            ->execute([$id, SubscriptionGroup::FIRST_CIRCLE_NAME, SubscriptionGroup::TYPE_FIRST_CIRCLE]);

        $groupId = $this->getEntityManager()
                        ->getConnection()
                        ->lastInsertId();

        $this->getEntityManager()
             ->getConnection()
             ->prepare('INSERT INTO bm2_social_test.member (id, role_id, city_id, first_circle_id) VALUES(?, ?, ?, ?)')
             ->execute([$id, SecurityService::ROLE_MEMBER, $cityId, $groupId]);

        return $this;
    }

    /**
     * Добавить тег
     *
     * @param int    $id
     * @param int    $typeId
     * @param string $name
     *
     * @return $this
     */
    public function createTag($id, $typeId, $name)
    {
        $this->overrideEntityManagerConnection();
        $this->getEntityManager()
             ->getConnection()
             ->prepare('INSERT INTO bm2_social_test.tag (id, type_id, name) VALUES(?, ?, ?)')
             ->execute([$id, $typeId, $name]);

        return $this;
    }

    /**
     * @return EntityManager
     */
    public function getEntityManager()
    {
        return $this->serviceManager->get('Doctrine\ORM\EntityManager');
    }

    /**
     * @param $memberId
     * @return \Member\Entity\Member
     */
    public function getMember($memberId)
    {
        /** @var \Member\Repository\Member $memberRepository */
        $memberRepository = $this->getEntityManager()->getRepository('Member\Entity\Member');

        return $memberRepository->getById($memberId);
    }
}
