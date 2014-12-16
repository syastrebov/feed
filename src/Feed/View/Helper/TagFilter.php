<?php

namespace Feed\View\Helper;

use Application\Repository\Tag as TagRepository;
use Zend\I18n\View\Helper\AbstractTranslatorHelper;

/**
 * Лента постов / Фильтр выбора по тегам
 *
 * Class TagFilter
 * @package Fee\View\Helper
 */
class TagFilter extends AbstractTranslatorHelper
{
    /**
     * @var TagRepository
     */
    private $repository;

    /**
     * Constructor
     *
     * @param TagRepository $repository
     */
    public function __construct(TagRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Invoke view helper
     *
     * @param int $type
     * @return array
     */
    public function __invoke($type)
    {
        $collection = $this->repository->findBy(['typeId' => $type]);
        $list = [[
            'name'  => '',
            'value' => 0
        ]];

        foreach ($collection as $tag) {
            /** @var \Application\Entity\Tag $tag */
            $list[] = [
                'name' => $tag->getName(),
                'value' => $tag->getId()
            ];
        }

        return $list;
    }
}