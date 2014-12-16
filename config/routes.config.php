<?php

use Feed\Service\Feed\GetList\Tab\FeedTabInterface;

return [
    'feed.list.rivers' => [
        'type'    => 'literal',
        'options' => [
            'route'    => '/feed/rivers/',
            'defaults' => [
                'controller' => 'Feed\Controller\Index',
                'action'     => 'feedList',
                'title'      => 'Реки',
                'type'       => FeedTabInterface::TYPE_ALL,
            ],
        ],
        'allow' => [
            'ROLE_GUEST'
        ],
    ],

    'feed.list.my' => [
        'type'    => 'literal',
        'options' => [
            'route'    => '/feed/my/',
            'defaults' => [
                'controller' => 'Feed\Controller\Index',
                'action'     => 'feedList',
                'title'      => 'Поток',
                'type'       => FeedTabInterface::TYPE_MY,
            ],
        ],
        'allow' => [
            'ROLE_MEMBER'
        ],
    ],

    'feed.list.product' => [
        'type'    => 'segment',
        'options' => [
            'route'       => '/feed/product/:id/',
            'constraints' => [
                'id'      => '[0-9]+',
            ],
            'defaults' => [
                'controller' => 'Feed\Controller\Index',
                'action'     => 'feedList',
                'title'      => 'Продукт',
                'type'       => FeedTabInterface::TYPE_PRODUCT,
            ],
        ],
        'allow' => [
            'ROLE_MEMBER'
        ],
    ],

    'feed.list.niche' => [
        'type'    => 'segment',
        'options' => [
            'route'       => '/feed/niche/:id/',
            'constraints' => [
                'id'      => '[0-9]+',
            ],
            'defaults' => [
                'controller' => 'Feed\Controller\Index',
                'action'     => 'feedList',
                'title'      => 'Интерес',
                'type'       => FeedTabInterface::TYPE_NICHE,
            ],
        ],
        'allow' => [
            'ROLE_MEMBER'
        ],
    ],

    'feed.list.post' => [
        'type'    => 'segment',
        'options' => [
            'route'       => '/feed/post/:id/',
            'constraints' => [
                'id'      => '[0-9]+',
            ],
            'defaults' => [
                'controller' => 'Feed\Controller\Index',
                'action'     => 'feedListPost'
            ],
        ],
        'allow' => [
            'ROLE_GUEST'
        ],
    ],

    'feed.post' => [
        'type'    => 'literal',
        'options' => [
            'route'    => '/feed/post/',
            'defaults' => [
                'controller' => 'Feed\Controller\Index',
                'action'     => 'feedPost',
                'title'      => 'Написать'
            ],
        ],
        'allow' => [
            'ROLE_MEMBER'
        ],
    ],

    'feed.edit' => [
        'type'    => 'Segment',
        'options' => [
            'route'    => '/feed/edit/:id/',
            'constraints' => [
                'id'     => '[0-9]+',
            ],
            'defaults' => [
                'controller' => 'Feed\Controller\Index',
                'action'     => 'feedEdit',
            ],
        ],
        'allow' => [
            'ROLE_MEMBER'
        ],
    ],

    'feed.rest' => [
        'type'    => 'Segment',
        'options' => [
            'route'    => '/api/post[/:id[/]]',
            'constraints' => [
                'id'     => '[0-9]+',
            ],
            'defaults' => [
                'controller' => 'Feed\Controller\Rest',
            ],
        ],
        'allow' => [
            'ROLE_GUEST'
        ],
    ],

    'feed.hidePost' => [
        'type'    => 'Segment',
        'options' => [
            'route'    => '/api/post/:type/:id[/]',
            'constraints' => [
                'type'   => 'show|hide',
                'id'     => '[0-9]+',
            ],
            'defaults' => [
                'controller' => 'Feed\Controller\Index',
                'action'     => 'hidePost',
            ],
        ],
        'allow' => [
            'ROLE_MEMBER'
        ],
    ],
];
