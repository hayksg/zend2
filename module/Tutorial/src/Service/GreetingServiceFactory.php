<?php

namespace Tutorial\Service;

use Zend\ServiceManager\Factory\FactoryInterface;
use Interop\Container\ContainerInterface;

class GreetingServiceFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $container->get('ModuleManager')->getEventManager()->getSharedManager()->attach(
            'greetingIdentifier',
            'getGreeting',
            function ($e) use ($container) {
                $params = $e->getParams();
                $container->get('eventService')->onGetGreeting($params);
            },
            100
        );

        $greetingService = new GreetingService();
        $greetingService->setEventManager($container->get('EventManager'));

        /*$greetingService->getEventManager()->attach(
            'getGreeting',
            function ($e) use ($container) {
                $params = $e->getParams();
                $container->get('eventService')->onGetGreeting($params);
            },
            100
        );*/

        /*$greetingAggregate = $container->get('greetingAggregate');
        $greetingAggregate->attach($greetingService->getEventManager());*/

        return $greetingService;
    }
}
