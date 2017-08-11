<?php

namespace Tutorial;

use Tutorial\Controller\IndexController;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return [
            'invokables' => [
                'greetingService' => Service\GreetingService::class,
            ],
        ];
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                IndexController::class => function ($container) {
                    $indexController = new IndexController();
                    $indexController->setGreetingService($container->get('greetingService'));
                    return $indexController;
                },
            ],
        ];
    }
}
