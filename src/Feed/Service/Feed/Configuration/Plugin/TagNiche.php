<?php

namespace Feed\Service\Feed\Configuration\Plugin;

use Application\Entity\Tag;
use Feed\Service\Feed\GetList\Entity\Configuration;
use Feed\Service\Feed\GetList\Tab\FeedTabInterface;
use Member\Entity\Member;

/**
 * Лента постов / Плагин задания конфигурации для выбора нишы (интересов)
 *
 * Class TagNiche
 * @package Feed\Service\Feed\Plugin\Configuration
 */
final class TagNiche extends AbstractPlugin
{
    /**
     * @var \Member\Entity\Member
     */
    private $identity;

    /**
     * Constructor
     *
     * @param Member $identity
     */
    public function __construct(Member $identity)
    {
        $this->identity = $identity;
    }

    /**
     * Тип плагина
     *
     * @return int
     */
    public function getType()
    {
        return ConfigurationPluginInterface::TYPE_TAG_NICHE;
    }

    /**
     * Запустить плагин
     *
     * @return boolean
     */
    public function shouldStart()
    {
        switch ($this->getTabTypeFromQuery()) {
            case FeedTabInterface::TYPE_ALL:
                return true;
            case FeedTabInterface::TYPE_NICHE:
                return $this->getTagNicheFromQuery() > 0;
        }

        return false;
    }

    /**
     * Применить плагин
     *
     * @param Configuration $config
     * @return Configuration
     */
    public function apply(Configuration $config)
    {
        if ($this->getTagNicheFromQuery()) {
            $config->setNicheTags([$this->getTagNicheFromQuery()]);
        } else {
            if ($this->canGetOwnConfiguration()) {
                $config->setNicheTags($this->getOwnNicheTags());
            }
        }

        return $config;
    }

    /**
     * Получить теги пользователя по интересам
     *
     * @return array
     */
    private function getOwnNicheTags()
    {
        $tags = $this->identity->getTags();
        $ids  = [];

        foreach ($tags as $tag) {
            /** @var Tag $tag */
            if ($tag->getTypeId() === Tag::TYPE_FEED_NICHE) {
                $ids[] = $tag->getId();
            }
        }

        return $ids;
    }

    /**
     * Можно использовать собственную конфигурацию
     *
     * @return bool
     */
    private function canGetOwnConfiguration()
    {
        return $this->identity->getId() > 0 && !$this->getTagNicheFromQuery() && !$this->getTagProductFromQuery();
    }
}