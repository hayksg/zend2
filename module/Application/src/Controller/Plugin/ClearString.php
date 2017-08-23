<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class ClearString extends AbstractPlugin
{
    public function __invoke($string)
    {
        $filterChain = new \Zend\Filter\FilterChain();
        $filterChain->attachByName('StripTags');
        $filterChain->attachByName('StringTrim');
        $string = $filterChain->filter($string);

        return $string ?: false;
    }
}
