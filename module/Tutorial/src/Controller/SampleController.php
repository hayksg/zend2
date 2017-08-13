<?php

namespace Tutorial\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class SampleController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel([

        ]);
    }

    public function exampleAction()
    {
        return new ViewModel([

        ]);
    }
}
