<?php

namespace Application\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

class TopNavigationService extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'topNavigation';
    }
}
