<?php

namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetParentCategoryName extends AbstractHelper
{
    public function __invoke($categories, $category)
    {
        foreach ($categories as $value) {
            if ($value->getId() === $category->getParent()) {
                $res = $value->getName();
                break;
            } else {
                $res = 'Has not parent category';
            }
        }
        return $res;
    }
}
