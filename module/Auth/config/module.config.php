<?php

namespace Auth;

return [
    'controllers' => [
        'factories' => [
            'Auth\Controller\Registration' => 'Auth\Factory\Controller\RegistrationControllerFactory',
            'Auth\Controller\Login' => 'Auth\Factory\Controller\LoginControllerFactory',
        ],
    ],
    'router' => [
        'routes' => [
            'registration' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/registration[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Auth\Controller\Registration',
                        'action'     => 'index',
                    ],
                ],
            ],
            'auth' => [
                'type'    => 'segment',
                'options' => [
                    'route'    => '/login[/:action][/:id]',
                    'constraints' => [
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => 'Auth\Controller\Login',
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'view_manager' => [
        'template_path_stack' => [
            'auth' => __DIR__ . '/../view',
        ],
    ],
];
