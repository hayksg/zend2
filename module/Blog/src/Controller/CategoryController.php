<?php

namespace Blog\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;

class CategoryController extends AbstractActionController
{
    private $entityManager;
    private $repository;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->repository = $entityManager->getRepository(Category::class);
    }

    public function indexAction()
    {
        $id = intval($this->getEvent()->getRouteMatch()->getParam('id', 0));
        $category = $this->repository->findBy(['id' => $id]);

        if (! $category) {
            return $this->notFoundAction();
        }

        return new ViewModel([
            'category' => $category[0],
        ]);
    }
}
