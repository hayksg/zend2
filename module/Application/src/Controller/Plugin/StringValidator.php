<?php

namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\Validator\ValidatorChain;

class StringValidator extends AbstractPlugin
{
    public function __invoke($value)
    {
        $validatorChain = new ValidatorChain();
        $validatorChain->attachByName('StringLength', ['min' => 1, 'max' => 2]);
        $validatorChain->attachByName('Alpha');
        $validatorChain->attachByName('NotEmpty');
        if ($validatorChain->isValid($value)) {
            return $value;
        } else {
            return $validatorChain->getMessages();
        }
    }
}
