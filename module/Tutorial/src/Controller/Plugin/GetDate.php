<?php

namespace Tutorial\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GetDate extends AbstractPlugin
{
    public function __invoke()
    {
        $dt = new \DateTime('now', new \DateTimeZone('Asia/Yerevan'));
        $date = $dt->format('F d Y');
        return $date ?: false;
    }
}
