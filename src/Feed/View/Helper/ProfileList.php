<?php

namespace Feed\View\Helper;

use Feed\Service\Feed\GetList\Tab\FeedTabInterface;
use Feed\Service\Feed\FeedBuilder;
use Zend\I18n\View\Helper\AbstractTranslatorHelper;
use Zend\Stdlib\Parameters;

/**
 * Лента постов / Список постов на странице профиля
 *
 * Class ProfileList
 * @package Feed\View\Helper
 */
class ProfileList extends AbstractTranslatorHelper
{
    /**
     * @var FeedBuilder
     */
    private $feedBuilder;

    /**
     * Constructor
     *
     * @param FeedBuilder $feedBuilder
     */
    public function __construct(FeedBuilder $feedBuilder)
    {
        $this->feedBuilder = $feedBuilder;
    }

    /**
     * Invoke view helper
     *
     * @return string
     */
    public function __invoke()
    {
        return $this->getView()->render('feed/helper/list/profile', [
            'feedList' => $this->feedBuilder->getList(new Parameters(['type' => FeedTabInterface::TYPE_PROFILE])),
        ]);
    }
}