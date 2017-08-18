<?php

namespace Tutorial\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ProductController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel([

        ]);
    }

    public function addAction()
    {
        return new ViewModel([

        ]);
    }

    public function postAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id', 0);
        $title = '';

        if ($this->request->isPost()) {
            $title = $this->request->getPost('title');
        }

        return new ViewModel([
            'id'    => $id,
            'title' => $title,
        ]);
    }
}
