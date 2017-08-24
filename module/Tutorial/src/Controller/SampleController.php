<?php

namespace Tutorial\Controller;

use Zend\Http\Headers;
use Zend\Http\Response\Stream;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Blog\Controller\IndexController;
use Zend\Session\Container;
use Zend\Http\Header\SetCookie;

class SampleController extends AbstractActionController
{
    const DS = DIRECTORY_SEPARATOR;

    public function indexAction()
    {
        /*if ($this->request->isPost()) {
            $this->prg();
        }*/

        $message = '';

        /*$container = new Container('addProduct');
        $message = $container->message;
        $container->getManager()->getStorage()->clear('addProduct');*/

        $cookie = $this->request->getCookie('addProduct');
        if ($cookie->offsetExists('addProduct')) {
            $message = $cookie->offsetGet('addProduct');

            $cookie = new SetCookie('addProduct', '', strtotime('-1 hours'), '/');
            $this->response->getHeaders()->addHeader($cookie);
        }

        return new ViewModel([
            'url' => $this->url()->fromRoute(),
            'date' => $this->getDate(),
            'message' => $message,
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

    public function fooAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $title = $request->getPost('title');
            if (empty($title)) {
                $message = 'An error occurred';
            } else {
                $message = 'Product successfully added';
            }
        }

        /*$container = new Container('addProduct');
        $container->message = $message;*/

        $cookie = new SetCookie('addProduct', $message, strtotime('+30 days'), '/');
        $this->response->getHeaders()->addHeader($cookie);

        $this->redirect()->toRoute('tutorial/sample');
    }

    public function downloadAction()
    {
        chdir('public/img/tutorial');
        $file = getcwd() . self::DS . 'c.jpg';

        if (is_file($file)) {
            $fileName = basename($file);
            $fileSize = filesize($file);

            $stream = new Stream();
            $stream->setStream(fopen($file, 'r'));
            $stream->setStreamName($fileName);
            $stream->setStatusCode(200);

            $headers = new Headers();
            $headers->addHeaderLine('Content-Type: application/octet-stream');
            $headers->addHeaderLine('Content-Disposition: attachment; filename=' . $fileName);
            $headers->addHeaderLine('Content-Length: ' . $fileSize);
            $headers->addHeaderLine('Cache-Control: no-store, must-revalidate');

            $stream->setHeaders($headers);
            return $stream;
        } else {
            return false;
        }
    }
}
