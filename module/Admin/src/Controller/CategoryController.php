<?php

namespace Admin\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;
use Application\Entity\Article;
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

                // If user will choose category without parent
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
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id', 0);
        $category = $this->categoryRepository->find($id);

        if (! $id || ! $category) {
            return $this->notFoundAction();
        }

        $form = $this->formService->getAnnotationForm($this->entityManager, $category);
        $form->setValidationGroup(['name', 'parent', 'isPublic']);

        /* Removes editing category from parents list */
        $this->clearCategory($form, 'parent', 'name');

        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());

            /* In order not allow to repeat category name */
            $oldName = $this->clearString($category->getName());
            $newName = $this->clearString($request->getPost('name'));

            if ($this->categoryRepository->findOneBy(['name' => $newName]) && $newName !== $oldName) {
                $message = "Category with name {$newName} exists already";
                $form->get('name')->setMessages([$message]);
            }
            /* End block */

            if ($form->isValid() && empty($form->getMessages())) {
                $category = $form->getData();

                // If user will choose category without parent
                if ($category->getParent() == 0) {
                    $category->setParent(null);
                }

                $this->entityManager->persist($category);
                $this->entityManager->flush();

                $this->flashMessenger()->addSuccessMessage('Category successfully edited.');
                return $this->redirect()->toRoute('admin/category');
            }
        }

        return new ViewModel([
            'id'   => $id,
            'form' => $form,
        ]);
    }

    public function deleteAction()
    {
        $id = (int)$this->getEvent()->getRouteMatch()->getParam('id', 0);
        $category = $this->categoryRepository->find($id);
        $request = $this->getRequest();

        if (! $id || ! $category || ! $request->isPost()) {
            return $this->notFoundAction();
        }

        /* Block for deletion nested articles images (on server) (If category has nested categories) */
        $nestedCategoriesChain = $this->getNestedCategoriesChain($id);

        array_walk_recursive($nestedCategoriesChain, function($category) {
            $articles = $this->entityManager->getRepository(Article::class)->findBy(['category' => $category->getId()]);

            if (isset($articles)) {
                array_walk_recursive($articles, function($article){
                    if (is_file(getcwd() . '/public' . $article->getImage())) {
                        unlink(getcwd() . '/public' . $article->getImage());
                    }
                });
            }
        });
        /* End block */

        /* Block for deletion articles images in category (on server) (If category has not nested categories) */
        $articles = $this->entityManager->getRepository(Article::class)->findBy(['category' => $category]);

        if ($articles) {
            foreach ($articles as $article) {
                if (is_file(getcwd() . '/public' . $article->getImage())) {
                    unlink(getcwd() . '/public' . $article->getImage());
                }
            }
        }
        /* End block */

        $this->entityManager->remove($category);
        $this->entityManager->flush();

        $this->flashMessenger()->addSuccessMessage('Category successfully deleted.');
        return $this->redirect()->toRoute('admin/category');
    }

    /* Removes editing category from parents list */
    private function clearCategory($form, $field1, $field2)
    {
        $categories = $form->get($field1)->getValueOptions();
        $arr = [];

        if (is_array($categories)) {
            foreach ($categories as $category) {
                if (isset($category['label']) && $form->get($field2)->getValue()) {
                    if($category['label'] == $form->get($field2)->getValue()) {
                        unset($category);
                        continue;
                    }
                    $arr[] = $category;

                    $form->get($field1)->setValueOptions($arr);
                }
            }
        }
    }

    private function getNestedCategoriesChain($categoryId)
    {
        $result = [];
        $categories = $this->entityManager->getRepository(Category::class)->findBy(['parent' => $categoryId]);
        if (! empty($categories)) {
            foreach ($categories as $category) {
                if (! empty($category) && count($category) !== 0) {
                    $result[] = $category;
                    $result[] = $this->getNestedCategoriesChain($category->getId());
                }
            }
        }

        return $result;
    }
}
