<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;
use Application\Service\FormServiceInterface;

class CategoryController extends AbstractActionController
{
    private $entityManager;
    private $categoryRepository;
    private $formService;

    public function __construct(
        EntityManagerInterface $entityManager,
        FormServiceInterface $formService
    ) {
        $this->entityManager = $entityManager;
        $this->categoryRepository = $this->entityManager->getRepository(Category::class);
        $this->formService = $formService;
    }

    public function indexAction()
    {
        $categories = $this->categoryRepository->findAll();

        return new ViewModel([
            'categories' => $categories,
        ]);
    }

    public function addAction()
    {
        $category = new Category();
        $form = $this->formService->getAnnotationForm($this->entityManager, $category);

        $request = $this->getRequest();
        if ($request->isPost()) {

            if ($this->isObjectExists($this->categoryRepository, $request->getPost('name'), ['name'])) {
                $message = "Category with name {$request->getPost('name')} exists already";
                $form->get('name')->setMessages(['nameExists' => $message]);
            }

            $form->setData($request->getPost());

            if ($form->isValid() && empty($form->getMessages())) {
                $category = $form->getData();

                if ($category->getParent() == 0) {
                    $category->setParent(null);
                }

                $this->entityManager->persist($category);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('Category successfully added.');
                return $this->redirect()->toRoute('admin/category');
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
