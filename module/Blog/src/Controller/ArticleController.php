<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Article;

class ArticleController extends AbstractActionController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Article::class);
    }

    public function indexAction()
    {
        $id = intval($this->getEvent()->getRouteMatch()->getParam('id', 0));
        $article = $this->repository->find($id);

        if (! $article) {
            return $this->notFoundAction();
        }

        return new ViewModel([
            'article' => $article,
        ]);
    }
}
