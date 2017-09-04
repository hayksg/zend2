<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Article;
use Zend\Paginator\Paginator;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator;
use Application\Service\FormServiceInterface;

class ArticleController extends AbstractActionController
{
    private $entityManager;
    private $articleRepository;
    private $formService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService
    ) {
        $this->entityManager = $entityManager;
        $this->articleRepository = $this->entityManager->getRepository(Article::class);
        $this->formService = $formService;
    }

    public function indexAction()
    {
        $result = $this->getPaginator();

        return new ViewModel([
            'pageCount' => $result['pageCount'],
            'articles'  => $result['paginator'],
        ]);
    }

    private function getPaginator()
    {
        $paginator = '';
        $queryBuilder = $this->articleRepository->getQueryBuilder(false);

        $adaptor = new DoctrinePaginator(new ORMPaginator($queryBuilder));
        $paginator = new Paginator($adaptor);

        $currentPageNumber = intval($this->params()->fromRoute('page', 0));
        $paginator->setCurrentPageNumber($currentPageNumber);

        $itemCountPerPage = 2;
        $paginator->setItemCountPerPage($itemCountPerPage);

        if ($currentPageNumber && $itemCountPerPage) {
            $pageCount = ($currentPageNumber - 1) * $itemCountPerPage;
        } else {
            $pageCount = 0;
        }

        return ['paginator' => $paginator, 'pageCount' => $pageCount];
    }

    public function addAction()
    {
        $article = new Article();
        $form = $this->formService->getAnnotationForm($this->entityManager, $article);
        $form->setValidationGroup('title');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            if ($form->isValid()) {
                $article = $form->getData();
                var_dump($article);
            }
        }

        return new ViewModel([
            'form' => $form,
        ]);
    }

    public function editAction()
    {
        return new ViewModel();
    }

    public function deleteAction()
    {
        return new ViewModel();
    }
}
