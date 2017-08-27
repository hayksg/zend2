<?php

namespace Admin\Service;

use Zend\Navigation\Service\DefaultNavigationFactory;

class AdminBreadcrumbService extends DefaultNavigationFactory
{
    protected function getName()
    {
        return 'admin_breadcrumb';
    }
}
