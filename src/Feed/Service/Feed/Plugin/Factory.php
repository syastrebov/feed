<?php

namespace Feed\Service\Feed\Plugin;

use BmComment\Service\Comment as CommentService;
use Feed\Service\Feed\Plugin\Service as FeedPluginService;
use Feed\Service\Feed\Plugin\Plugin;
use Feed\Service\Feed\Plugin\Adapter\Doctrine as Adapter;
use Feed\Service\Feed\Cache\Adapter\Plugin as CacheAdapter;
use Feed\Service\Feed\Plugin\Plugin\Content\Collection as ContentCollection;
use Feed\Service\Feed\Plugin\Plugin\Filter\Collection as FilterCollection;
use Member\Repository\Member as MemberRepository;
use Member\Entity\Member as MemberEntity;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Лента постов / Фабрика создания сервиса плагинов ленты
 *
 * Class Service
 * @package Application\Service\Feed\Factory
 */
class Factory implements FactoryInterface
{
    /**
     * @var ServiceLocatorInterface
     */
    private $serviceLocator;

    /**
     * Создаем сервис
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return FeedPluginService
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;

        $filterCollection = new FilterCollection();
        $filterCollection->attach($this->getIDontWantToSeeThisFilterPlugin());

        $contentCollection = new ContentCollection();
        $contentCollection
            ->attach($this->getPostContentPlugin())
            ->attach($this->getUserContentPlugin())
            ->attach($this->getUserSubscribeContentPlugin())
            ->attach($this->getLikeContentPlugin())
            ->attach($this->getCommentContentPlugin())
            ->attach($this->getTagContentPlugin())
            ->attach($this->getFileContentPlugin());

        return new FeedPluginService($filterCollection, $contentCollection);
    }

    /**
     * Ссылка на объект сессии пользователя
     *
     * @return MemberEntity
     */
    private function getIdentity()
    {
        /** @var \Member\Security\Service $securityService */
        $securityService = $this->serviceLocator->get('UserService')->getSecurityService();
        return $securityService->getIdentity();
    }

    /**
     * Является ли пользователь админом
     *
     * @return bool
     */
    private function isAdmin()
    {
        /** @var \Member\Security\Service $securityService */
        $securityService = $this->serviceLocator->get('UserService')->getSecurityService();
        return $securityService->isAdmin();
    }

    /**
     * Doctrine менеджер моделей
     *
     * @return \Doctrine\ORM\EntityManager
     */
    private function getEntityManager()
    {
        return $this->serviceLocator->get('Doctrine\ORM\EntityManager');
    }

    /**
     * Обертка для кеша для доктрины
     *
     * @return \Doctrine\Common\Cache\Cache $doctrineCache
     */
    private function getDoctrineCache()
    {
        return $this->serviceLocator->get('doctrine.cache.zend.static.local');
    }

    /**
     * Плагин для загрузки тела сообщения
     *
     * @return Plugin\Content\Post
     */
    private function getPostContentPlugin()
    {
        return new Plugin\Content\Post(new CacheAdapter\Post(
            $this->getDoctrineCache(),
            new Adapter\Post($this->getEntityManager())
        ));
    }

    /**
     * Плагин загрузки автора поста
     *
     * @return Plugin\Content\User
     */
    private function getUserContentPlugin()
    {
        return new Plugin\Content\User(new CacheAdapter\User(
            $this->getDoctrineCache(),
            new Adapter\User($this->getEntityManager())
        ));
    }

    /**
     * Плагин загрузки подписан/неподписан на автора
     *
     * @return Plugin\Content\UserSubscribe
     */
    private function getUserSubscribeContentPlugin()
    {
        /** @var MemberRepository $memberRepository */
        $memberRepository = $this->getEntityManager()->getRepository('Member\Entity\Member');
        return new Plugin\Content\UserSubscribe($this->getIdentity(), $memberRepository);
    }

    /**
     * Плагин для загрузки комментариев
     *
     * @return Plugin\Content\Comment
     */
    private function getCommentContentPlugin()
    {
        /** @var CommentService $commentService */
        $commentService = $this->serviceLocator->get('SocialCommentService');
        return new Plugin\Content\Comment($commentService, $this->isAdmin());
    }

    /**
     * Плагин для загрузки лайков
     *
     * @return Plugin\Content\Like
     */
    private function getLikeContentPlugin()
    {
        /** @var \Social\Service\Like $likeService */
        $likeService = $this->serviceLocator->get('SocialLikeService');
        return new Plugin\Content\Like($this->getIdentity(), $likeService);
    }

    /**
     * Плагин для загрузки тегов
     *
     * @return Plugin\Content\Tag
     */
    private function getTagContentPlugin()
    {
        return new Plugin\Content\Tag(new CacheAdapter\Tag(
            $this->getDoctrineCache(),
            new Adapter\Tag($this->getEntityManager())
        ));
    }

    /**
     * Плагин для загрузки файлов
     *
     * @return Plugin\Content\File
     */
    private function getFileContentPlugin()
    {
        return new Plugin\Content\File(new CacheAdapter\File(
            $this->getDoctrineCache(),
            new Adapter\File($this->getEntityManager())
        ));
    }

    /**
     * Фильтр "я не хочу это видеть"
     *
     * @return Plugin\Filter\IDontWantToSeeThis
     */
    private function getIDontWantToSeeThisFilterPlugin()
    {
        return new Plugin\Filter\IDontWantToSeeThis(
            $this->getIdentity(),
            new CacheAdapter\UserHiddenPosts(
                $this->getDoctrineCache(),
                new Adapter\UserHiddenPosts($this->getEntityManager()
            ))
        );
    }
}
