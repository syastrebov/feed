<?php

return [

    'controllers' => [
        'invokables' => [
            'Feed\Controller\Index' => 'Feed\Controller\Index',
            'Feed\Controller\Rest'  => 'Feed\Controller\Rest',
        ]
    ],

    'service_manager' => [
        'invokables' => [
            'FeedSpamListener' => 'Feed\Listener\Spam',
        ],
        'factories' => [
            'FeedService'              => 'Feed\Service\Feed\GetList\Factory',
            'FeedPluginService'        => 'Feed\Service\Feed\Plugin\Factory',
            'FeedConfigurationBuilder' => 'Feed\Service\Feed\Configuration\Factory',
            'FeedPhantomJsListener'    => 'Feed\Service\Feed\PhantomJs\Factory',
        ],
    ],

    'router' => [
        'routes' => require __DIR__ . '/routes.config.php',
    ],

    'view_manager' => [
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],

    'doctrine' => [
        'driver' => [
            'feed_entities' => [
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => [__DIR__ . '/../src/Feed/Entity']
            ],

            'orm_default' => [
                'drivers' => [
                    'Feed\Entity' => 'feed_entities'
                ]
            ]
        ]
    ],

];