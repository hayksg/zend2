<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        if ($this->request->isPost()) {
            $postValue = $this->request->getPost('title');
            //$postValue = $this->clearString($postValue);
            $postValue = $this->stringValidator($postValue);
            var_dump($postValue);
        }

        return new ViewModel();
    }
}
