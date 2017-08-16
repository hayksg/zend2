<?php

namespace Tutorial\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Controller\IndexController;

class SampleController extends AbstractActionController
{
    public function indexAction()
    {
        /*if ($this->request->isPost()) {
            $this->prg();
        }*/

        return new ViewModel([
            'url' => $this->url()->fromRoute(),
            'date' => $this->getDate(),
        ]);
    }

    public function exampleAction()
    {
        //return $this->redirect()->toUrl('http://rambler.ru');
        //return $this->forward()->dispatch(IndexController::class, ['action' => 'index']);

        //$this->layout('layout/layoutSecond');

        //$successMessage = 'Success message';
        //$this->flashMessenger()->addSuccessMessage($successMessage);

        /*$errorMessage = 'Error message';
        $this->flashMessenger()->addErrorMessage($errorMessage);
        return $this->redirect()->toRoute('tutorial/sample');*/

        $widget = $this->forward()->dispatch(IndexController::class, ['action' => 'index']);

        $view = new ViewModel([]);
        $view->addChild($widget, 'widget');
        $view->setTemplate('tutorial/sample/exampleNew');
        return $view;
    }
}
