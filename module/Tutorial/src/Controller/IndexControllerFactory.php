<?php

namespace Tutorial\Controller;

use Tutorial\Service\GreetingService;
use Tutorial\Service\GreetingServiceInterface;
use Zend\Di\Config;
use Zend\Di\Di;
use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class IndexControllerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /*$di = new Di();
        $di->configure(new Config([
            'definition' => [
                'class' => [
                    IndexController::class => [
                        'setGreetingService' => [
                            'required' => true,
                        ],
                    ],
                ],
            ],
            'instance' => [
                'preferences' => [
                    GreetingServiceInterface::class => GreetingService::class,
                ],
            ],
        ]));

        $indexController = $di->get(IndexController::class);
        return $indexController;*/


        /*$indexController = new IndexController();
        $indexController->setGreetingService($container->get('greetingService'));
        return $indexController;*/
    }
}
