<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Feed\Service\Feed\GetList\Tab\FeedTabInterface;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Exception\NotFoundException;
use Feed\Service\Feed\Configuration\Adapter\SubscriberInterface;
use Feed\Service\Feed\Plugin\Adapter\UserInterface;
use Member\Entity\Member;
use Member\Entity\Subscription;

/**
 * Лента постов / Плагин задания конфигурации для выбора списка пользователей
 *
 * Class Owner
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class Owner extends AbstractPlugin
{
    /**
     * @var \Member\Entity\Member
     */
    private $identity;

    /**
     * @var UserInterface
     */
    private $userAdapter;

    /**
     * @var SubscriberInterface
     */
    private $subscriberAdapter;

    /**
     * Constructor
     *
     * @param Member              $identity
     * @param UserInterface       $userAdapter
     * @param SubscriberInterface $subscriberAdapter
     */
    public function __construct(Member $identity, UserInterface $userAdapter, SubscriberInterface $subscriberAdapter)
    {
        $this->identity          = $identity;
        $this->userAdapter       = $userAdapter;
        $this->subscriberAdapter = $subscriberAdapter;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return ConfigurationPluginInterface::TYPE_OWNER;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        return in_array($this->getTabTypeFromQuery(), [
            FeedTabInterface::TYPE_MY,
            FeedTabInterface::TYPE_PROFILE,
        ], true);
    }

    /**
     * Применить плагин
     *
     * @param Configuration $config
     * @return Configuration
     * @throws NotFoundException
     */
    public function apply(Configuration $config)
    {
        switch ($this->getTabTypeFromQuery()) {
            // Вкладка профиль (отображается только посты пользователя, выбранного или авторизованного)
            case FeedTabInterface::TYPE_PROFILE:
                if ($this->getMemberIdFromQuery() && $this->getMemberIdFromQuery() !== $this->identity->getId()) {
                    $member = $this->userAdapter->getMembersByIds([$this->getMemberIdFromQuery()])->pop();
                    if (!$member) {
                        throw new NotFoundException('Не найден memberId');
                    }
                    /** @var Member $member */
                    $config->setOwnerIds([$member->getId()]);

                } else {
                    if (!($this->identity->getId() > 0)) {
                        throw new NotFoundException('Не найден memberId');
                    }

                    $config->setOwnerIds([$this->identity->getId()]);
                }

                break;

            // Вкладка поток (отображается только для авторизованного пользователя, его посты + его избранных)
            case FeedTabInterface::TYPE_MY:
                if (!($this->identity->getId() > 0)) {
                    throw new NotFoundException('Не найден memberId');
                }

                $favourites   = $this->subscriberAdapter->getSubscribers($this->identity->getId());
                $favouriteIds = [$this->identity->getId()];

                foreach ($favourites as $favourite) {
                    /** @var Subscription $favourite */
                    $favouriteIds[] = $favourite->getTargetId();
                }

                array_unique($favouriteIds);
                $config->setOwnerIds($favouriteIds);

                break;
        }

        return $config;
    }
}