<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Doctrine\ORM\EntityManagerInterface;
use Application\Entity\Category;

class GetCategory
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function __invoke()
    {
        $result = $this->entityManager
                       ->createQuery('SELECT c FROM Application\Entity\Category AS c WHERE c.isPublic = 1')
                       ->getResult();


        $categories = [];
        foreach ($result as $value) {
            $categories[$value->getParent()][] = $value;
        }

        return $this->buildTree($categories, null);
    }

    public function buildTree($cat, $catId)
    {
        $output = '';

        if (is_array($cat) && isset($cat[$catId])) {
            $output .= '<ul class="menu_vert">';
            foreach ($cat[$catId] as $category) {
                $output .= '<li><a href="/blog/category/' . $category->getId() . '">' . $category->getName() . '</a>';
                $output .= $this->buildTree($cat, $category->getId());
                $output .= '</li>';
            }
            $output .= '</ul>';
        }

        return $output;
    }
}
