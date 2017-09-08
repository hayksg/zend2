<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Article;
use Application\Entity\Category;
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

        if(! $this->getCategoryWhichHasNotParentCategory($form)) {
            return false;
        }

        $request = $this->getRequest();

        if ($request->isPost()) {
            $files = $request->getFiles()->toArray();
            if ($files) $fileName = $files['file']['name'];

            $data = array_merge_recursive($request->getPost()->toArray(), $files);
            $form->setData($data);

            if ($form->isValid()) {
                $article = $form->getData();

                if ($fileName) $article->setImage('/img/blog/' . $fileName);

                $this->entityManager->persist($article);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('Article successfully added.');
                return $this->redirect()->toRoute('admin/article');
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

    private function getCategoryWhichHasNotParentCategory($form)
    {
        $arr = [];
        $res = [];

        $categories = $this->entityManager->getRepository(Category::class)->findAll();
        foreach ($categories as $category) {
            $arr[] = $category->getParent();
        }

        $categories = $form->get('category')->getValueOptions();
        foreach ($categories as $category) {
            foreach ($arr as $value) {
                if (isset($category['value']) && $category['value'] == $value) {
                    unset($category);
                    continue 2;
                }
            }

            $res[] = $category;

            $form->get('category')->setValueOptions($res);
        }

        $categories = $form->get('category')->getValueOptions();
        return $categories ?: false;
    }
}
