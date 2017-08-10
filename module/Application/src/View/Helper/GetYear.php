<?php

namespace Application\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetYear extends AbstractHelper
{
    public function __invoke()
    {
        $dt = new \DateTime('now', new \DateTimeZone('Asia/Yerevan'));
        $year = $dt->format('Y');

        if ($year) {
            return ($year > 2017) ? "2017 - {$year}" : $year;
        }
        return false;
    }
}
