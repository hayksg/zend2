<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Article;

class IndexController extends AbstractActionController
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
        $articles = $this->repository->findBy(['isPublic' => 1], ['id' => 'DESC']);

        return new ViewModel([
            'articles' => $articles,
        ]);
    }
}
