<?php

namespace Feed\Controller;

use Application\Controller\AbstractController;
use Feed\Entity\FeedHidden;
use Feed\Exception\NotFoundException;
use Feed\Service\Feed\Configuration\Plugin\ConfigurationPluginInterface;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;
use Feed\Service\Feed\FeedBuilder as FeedBuilderService;
use Feed\Service\Feed\Cache\Clear as ClearEvent;
use Zend\Http\Response;
use Zend\Stdlib\Parameters;
use Zend\View\Model\JsonModel;
use Zend\View\Model\ViewModel;
use Zend\Http\Request as HttpRequest;
use Exception;

/**
 * Лента постов / Route контроллер
 *
 * Class Index
 * @package Feed\Controller
 */
class Index extends AbstractController
{
    /**
     * Список постов
     * Используется для отображения страниц река, поток, продукт и интерес
     */
    public function feedListAction()
    {
        // Определяем тип ленты
        $type = (int)$this->params()->fromRoute('type', 0);
        if (!in_array($type, [
            FeedTabInterface::TYPE_ALL,
            FeedTabInterface::TYPE_MY,
            FeedTabInterface::TYPE_PRODUCT,
            FeedTabInterface::TYPE_NICHE,
        ], true)) {
            return $this->notFoundAction();
        }

        $id = (int)$this->params()->fromRoute('id', 0);
        if (in_array($type, [FeedTabInterface::TYPE_PRODUCT, FeedTabInterface::TYPE_NICHE]) && !($id > 0)) {
            return $this->notFoundAction();
        }

        // Конфигуратор для параметров ленты
        $params = [ConfigurationPluginInterface::PARAM_TYPE => $type];
        if ($type === FeedTabInterface::TYPE_PRODUCT) {
            $params[ConfigurationPluginInterface::PARAM_PRODUCT] = $id;
        }
        if ($type === FeedTabInterface::TYPE_NICHE) {
            $params[ConfigurationPluginInterface::PARAM_NICHE] = $id;
        }

        /** @var FeedBuilderService $feedBuilder */
        $feedBuilder = $this->getServiceLocator()->get('FeedBuilderService');
        $viewModel   = new ViewModel([
            'feedType' => $type,
            'feedList' => $feedBuilder->getList(new Parameters($params)),
        ]);

        switch ($type) {
            case FeedTabInterface::TYPE_ALL:
                return $viewModel->setTemplate('feed/helper/list/rivers');
            case FeedTabInterface::TYPE_MY:
                return $viewModel->setTemplate('feed/helper/list/my');
            case FeedTabInterface::TYPE_PRODUCT:
                return $viewModel
                    ->setVariable('id', $id)
                    ->setTemplate('feed/helper/list/product');
            case FeedTabInterface::TYPE_NICHE:
                return $viewModel
                    ->setVariable('id', $id)
                    ->setTemplate('feed/helper/list/niche');
            default:
                return $this->notFoundAction();
        }
    }

    /**
     * Страница ленты одиночного поста
     */
    public function feedListPostAction()
    {
        try {
            /** @var FeedBuilderService $feedBuilder */
            $feedBuilder = $this->getServiceLocator()->get('FeedBuilderService');
            return [
                'post' => $feedBuilder->get((int)$this->params()->fromRoute('id', 0)),
            ];

        } catch (NotFoundException $e) {
            return $this->notFoundAction();
        }
    }

    /**
     * Страница написания нового поста
     *
     * @return array
     */
    public function feedPostAction()
    {
        return [];
    }

    /**
     * Редактирование поста
     *
     * @return array|ViewModel
     */
    public function feedEditAction()
    {
        try {
            /** @var FeedBuilderService $feedBuilder */
            $feedBuilder = $this->getServiceLocator()->get('FeedBuilderService');

            $view = new ViewModel(['post' => $feedBuilder->get((int)$this->params()->fromRoute('id', 0))]);
            $view->setTemplate('mobile/feed/index/feed-post');

            return $view;

        } catch (NotFoundException $e) {
            return $this->notFoundAction();
        }
    }

    /**
     * Пометить пост как "я не хочу это видеть"
     */
    public function hidePostAction()
    {
        /** @var HttpRequest $request */
        $request = $this->getRequest();
        if (!$request->isXmlHttpRequest()) {
            return $this->notFoundAction();
        }

        try {
            $feedId = (int)$this->params()->fromRoute('id', 0);
            if (!$feedId) {
                throw new NotFoundException();
            }

            /** @var \Doctrine\ORM\EntityManager $entityManager */
            $entityManager = $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
            $entity = $entityManager->getRepository('Feed\Entity\FeedHidden')->findOneBy([
                'userId' => $this->getMember()->getId(),
                'feedId' => $feedId,
            ]);

            // Сбрасываем кеш при сохранении
            $this->getEventManager()->trigger(
                ClearEvent\FeedClearCacheEventInterface::EVENT_TYPE,
                new ClearEvent\Plugin\UserHiddenPosts($this->getMember()->getId())
            );

            switch ($this->params()->fromRoute('type')) {
                case 'hide':
                    if (!$entity) {
                        $entity = new FeedHidden();
                        $entity
                            ->setFeedId($feedId)
                            ->setUserId($this->getMember()->getId());

                        $entityManager->persist($entity);
                        $entityManager->flush();
                    }

                    break;
                case 'show':
                    if (!$entity) {
                        throw new NotFoundException();
                    }

                    $entityManager->remove($entity);
                    $entityManager->flush();

                    break;
                default:
                    throw new Exception('Неизвестный тип');
            }


            return new JsonModel([]);

        } catch (Exception $e) {
            $response = new Response();
            $response->setStatusCode(500);

            return $response;
        }
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
