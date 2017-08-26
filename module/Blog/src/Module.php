<?php

namespace Blog;

use Blog\Controller\IndexController;
use Blog\Controller\ArticleController;
use Blog\Controller\CategoryController;
use Doctrine\ORM\EntityManager;

class Module
{
    const VERSION = '3.0.3-dev';

    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function getControllerConfig()
    {
        return [
            'factories' => [
                Controller\IndexController::class => function ($container) {
                    return new IndexController(
                        $container->get(EntityManager::class)
                    );
                },
                Controller\ArticleController::class => function ($container) {
                    return new ArticleController(
                        $container->get(EntityManager::class)
                    );
                },
                Controller\CategoryController::class => function ($container) {
                    return new CategoryController(
                        $container->get(EntityManager::class)
                    );
                },
            ],
        ];
    }
}
