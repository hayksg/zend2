<?php

namespace Tutorial;

use Zend\Router\Http\Literal;
use Zend\Router\Http\Segment;
use Zend\Router\Http\Regex;
use Zend\Router\Http\Method;
use Zend\ServiceManager\Factory\InvokableFactory;

return [
    'router' => [
        'routes' => [
            'tutorial' => [
                'type' => Literal::class,
                'options' => [
                    'route'    => '/tutorial',
                    'defaults' => [
                        'controller' => Controller\IndexController::class,
                        'action'     => 'index',
                    ],
                ],
                'may_terminate' => true,
                'child_routes' => [
                    'sample' => [
                        'type' => Segment::class,
                        'options' => [
                            'route'    => '/sample[/:action]',
                            'defaults' => [
                                'controller' => Controller\SampleController::class,
                                'action'     => 'index',
                            ],
                        ],
                    ],
                ],
            ],
            /*'product' => [
                'type' => Segment::class,
                'options' => [
                    'route'       => '/product[/:action[/:id]]',
                    'constraints' => [
                        'action' => '[a-z]+',
                        'id'     => '[0-9]+',
                    ],
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                        'action'     => 'index',
                        //'action'     => rand(0, 1) ? 'add' : 'post',
                    ],
                ],
            ],*/
            /*'product' => [
                'type' => Regex::class,
                'options' => [
                    'regex' => '/product(/(?<action>[a-z]+)(/(?<id>[0-9]+))?)?',
                    'spec'  => '/%action%/%id%',
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                        'action'     => 'index',
                    ],
                ],
            ],*/
            'productProcess' => [
                'type' => Segment::class,
                'options' => [
                    'route'  => '/product[/:action[/:id]]',
                    'constraints'  => [
                        'action' => '[a-z]+',
                        'id'     => '[0-9]+',
                    ],
                ],
                'child_routes' => [
                    'get' => [
                        'type' => Method::class,
                        'options' => [
                            'verb'  => 'get',
                            'defaults' => [
                                'controller' => Controller\ProductController::class,
                                'action'     => 'add',
                            ],
                        ],
                    ],
                    'post' => [
                        'type' => Method::class,
                        'options' => [
                            'verb'  => 'post',
                            'defaults' => [
                                'controller' => Controller\ProductController::class,
                                'action'     => 'post',
                            ],
                        ],
                    ],
                ],
            ],
            'product' => [
                'type' => Segment::class,
                'options' => [
                    'route'       => '/product',
                    'defaults' => [
                        'controller' => Controller\ProductController::class,
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],
    'controllers' => [
        'factories' => [
            //Controller\IndexController::class => InvokableFactory::class,
            //Controller\IndexController::class => Controller\IndexControllerFactory::class,
            Controller\SampleController::class => InvokableFactory::class,
            Controller\ProductController::class => InvokableFactory::class,
        ],
    ],
    'view_manager' => [
        'template_map' => [
            'tutorial/index/index' => __DIR__ . '/../view/tutorial/index/index.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
    ],
];
