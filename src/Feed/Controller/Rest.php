<?php

namespace Feed\Controller;

use Doctrine\Common\Collections\ArrayCollection;
use Feed\Entity\Feed as FeedEntity;
use Feed\Entity\FeedFile as FeedFileEntity;
use Application\Repository\Tag as TagRepository;
use Feed\Exception\NotFoundException;
use Feed\Service\Feed\GetList\Service as FeedService;
use Feed\Service\Feed\FeedBuilder as FeedBuilderService;
use BmCommon\Controller\AbstractRestfulController;
use Feed\Service\Feed\Cache\Clear as ClearEvent;
use Feed\Service\Feed\PhantomJs\Event as PhantomJsEvent;
use Zend\Http\Request as HttpRequest;
use DateTime;
use Exception;

/**
 * Лента постов / Restful контроллер
 *
 * Class Rest
 * @package Feed\Controller\Rest
 */
class Rest extends AbstractRestfulController
{
    /**
     * Добавление новой записи
     *
     * @param mixed $data
     * @return array
     * @throws Exception
     */
    public function create($data)
    {
        /** @var FeedService $feedService */
        $feedService = $this->getServiceLocator()->get('FeedService');
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var TagRepository $tagRepository */
        $tagRepository = $entityManager->getRepository('Application\Entity\Tag');

        if (!isset($data['text'])) {
            throw new Exception('Не передан текст');
        }

        $feedEntity = new FeedEntity();
        $feedEntity
            ->setMember($this->getMember())
            ->setText($data['text'])
            ->setAccess(isset($data['access']) ? $data['access'] : false)
            ->setCreated(new DateTime())
            ->setUpdated(new DateTime());

        if (!empty($data['tags'])) {
            $tags = $tagRepository->findBy(['id' => $data['tags']]);
            foreach ($tags as $tag) {
                $feedEntity->addTag($tag);
            }
        }
        if (isset($data['entryId'])) {
            /** @var \Application\Entity\Entry $entry */
            $entry = $entityManager->getRepository('Application\Entity\Entry')->find($data['entryId']);
            $feedEntity
                ->setTypeId(FeedEntity::FEED_ENTITY_TYPE_SIMPLE_GOAL)
                ->setUserEntry($entry)
                ->setTitle($entry->getTitle());
        } else {
            $feedEntity
                ->setTypeId(FeedEntity::FEED_ENTITY_TYPE_SIMPLE);
        }

        if (!empty($data['files'])) {
            $newFiles = new ArrayCollection();

            foreach ($data['files'] as $file) {
                $newFile = new FeedFileEntity();
                $newFile->setTypeId((int)$file['type_id'])
                        ->setPath($file['path'])
                        ->setFeed($feedEntity)
                        ->setCreated(new \DateTime());
                $newFiles->add($newFile);
            }
            $feedEntity->setFiles($newFiles);
        }

        $feedService->insert($feedEntity);

        // Обновляем закешированную страницу для поисковиков
        $this->getEventManager()->trigger(
            PhantomJsEvent\PhantomJsEventInterface::EVENT_TYPE,
            new PhantomJsEvent\Post($feedEntity)
        );

        return $feedEntity->toArray();
    }

    public function delete($id)
    {
        return ['delete'];
    }

    /**
     * Получить запись по id
     *
     * @param integer $id
     * @return array
     * @throws Exception
     */
    public function get($id)
    {
        /** @var HttpRequest $request */
        $request = $this->getRequest();
        if (!($request instanceof HttpRequest)) {
            throw new NotFoundException();
        }

        /** @var FeedBuilderService $feedBuilder */
        $feedBuilder = $this->getServiceLocator()->get('FeedBuilderService');
        return $feedBuilder->get($id);
    }

    /**
     * Получение списка записей
     *
     * @return array
     * @throws Exception
     */
    public function getList()
    {
        /** @var HttpRequest $request */
        $request = $this->getRequest();
        if (!($request instanceof HttpRequest)) {
            throw new NotFoundException();
        }

        /** @var FeedBuilderService $feedBuilder */
        $feedBuilder = $this->getServiceLocator()->get('FeedBuilderService');
        return $feedBuilder->getList($request->getQuery());
    }

    public function patch($id, $data)
    {
        return ['update', $id, $data];
    }

    /**
     * Обновить запись
     *
     * @param int   $id
     * @param array $data
     *
     * @return mixed
     * @throws \Exception
     */
    public function update($id, $data)
    {
        /** @var \Doctrine\ORM\EntityManager $entityManager */
        $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
        /** @var TagRepository $tagRepository */
        $tagRepository = $entityManager->getRepository('Application\Entity\Tag');
        /** @var \Feed\Repository\FeedFile $fileRepository */
        $fileRepository = $entityManager->getRepository('Feed\Entity\FeedFile');
        $feedRepository = $entityManager->getRepository('Feed\Entity\Feed');

        if (!isset($data['text'])) {
            throw new Exception('Не передан текст');
        }

        /** @var FeedEntity $feedEntity */
        $feedEntity = $feedRepository->findOneBy(['memberId'=>$this->getMember()->getId(), 'id'=>$id]);
        $feedEntity
            ->setText($data['text'])
            ->setAccess(isset($data['access']) ? $data['access'] : false)
            ->setUpdated(new DateTime())
            ->resetTags();

        if (!empty($data['tags'])) {
            $tagIds = [];
            foreach ($data['tags'] as $tag) {
                if (isset($tag['id']) && $tag['id'] > 0) {
                    $tagIds[] = $tag['id'];
                }
            }
            if (!empty($tagIds)) {
                $tags = $tagRepository->findBy(['id' => $tagIds]);
                foreach ($tags as $tag) {
                    $feedEntity->addTag($tag);
                }
            }
        }

        if (!empty($data['files'])) {
            $existentFiles = $feedEntity->getFiles();
            $newFiles = new ArrayCollection();
            $ids = [];

            foreach ($data['files'] as $file) {
                if (isset($file['id'])) {
                    $ids[] = $file['id'];
                } else {
                    $newFile = new FeedFileEntity();
                    $newFile->setTypeId((int)$file['type_id'])
                            ->setPath($file['path'])
                            ->setFeed($feedEntity)
                            ->setCreated(new \DateTime());
                    $newFiles->add($newFile);
                }
            }

            $idsToDelete = [];
            foreach ($existentFiles as $feedFile) {
                if (!in_array($feedFile->getId(), $ids)) {
                    $existentFiles->removeElement($feedFile);
                    $idsToDelete[] = $feedFile->getId();
                }
            }
            foreach ($newFiles as $newFile) {
                $existentFiles->add($newFile);
            }
            if ($idsToDelete) {
                $fileRepository->deleteByIds($idsToDelete);
            }
        }

        if (isset($data['entryId'])) {
            /** @var \Application\Entity\Entry $entry */
            $entry = $entityManager->getRepository('Application\Entity\Entry')->find($data['entryId']);
            $feedEntity
                ->setTypeId(FeedEntity::FEED_ENTITY_TYPE_SIMPLE_GOAL)
                ->setUserEntry($entry)
                ->setTitle($entry->getTitle());
        } else {
            $feedEntity
                ->setTypeId(FeedEntity::FEED_ENTITY_TYPE_SIMPLE);
        }

        // Сбрасываем кеш при сохранении
        $this->getEventManager()->trigger(
            ClearEvent\FeedClearCacheEventInterface::EVENT_TYPE,
            new ClearEvent\Plugin\Post($id)
        );
        $this->getEventManager()->trigger(
            ClearEvent\FeedClearCacheEventInterface::EVENT_TYPE,
            new ClearEvent\Plugin\Tag($id)
        );
        $this->getEventManager()->trigger(
            ClearEvent\FeedClearCacheEventInterface::EVENT_TYPE,
            new ClearEvent\Plugin\File($id)
        );

        $entityManager->flush();
        return $feedEntity->toArray();
    }

    /**
     * Получить объект пользователя
     *
     * @return \Member\Entity\Member
     */
    public function getMember()
    {
        return $this->getServiceLocator()->get('UserService')->getSecurityService()->getIdentity();
    }
}
