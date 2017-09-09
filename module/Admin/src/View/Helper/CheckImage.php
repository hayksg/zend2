<?php

namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class CheckImage extends AbstractHelper
{
    public function __invoke($img)
    {
        if (is_file(getcwd() . '/public' . $img)) {
            return $img;
        } else {
            return '/img/home/no-image.jpg';
        }
    }
}
