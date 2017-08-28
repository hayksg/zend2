<?php

namespace Admin;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'admin' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/admin',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'category' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/category[/:action[/:id]]',
                            'constraints'    => [
                                'action' => '[a-z]+',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\CategoryController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                    'article' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/article[/:action[/:id]]',
                            'constraints'    => [
                                'action' => '[a-z]+',
                                'id'     => '[0-9]+',
                            ],
                            'defaults' => [
                                'controller' => Controller\ArticleController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            Controller\IndexController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'admin/index/index' => __DIR__ . '/../view/admin/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
    'navigation' => [
        'admin_breadcrumb' => [
            'admin' => [
                'label' => 'Admin',
                'route' => 'admin',
                'pages' => [
                    'category' => [
                        'label' => 'Categories',
                        'route' => 'admin/category',
                        'pages' => [
                            'add' => [
                                'label'  => 'Add',
                                'route'  => 'admin/category',
                                'action' => 'add',
                            ],
                            'edit' => [
                                'label'  => 'Edit',
                                'route'  => 'admin/category',
                                'action' => 'edit',
                            ],
                        ],
                    ],
                    'article' => [
                        'label' => 'Articles',
                        'route' => 'admin/article',
                        'pages' => [
                            'add' => [
                                'label'  => 'Add',
                                'route'  => 'admin/article',
                                'action' => 'add',
                            ],
                            'edit' => [
                                'label'  => 'Edit',
                                'route'  => 'admin/article',
                                'action' => 'edit',
                            ],
                        ],
                    ],
                ],
            ],
        ],
    ]
];
