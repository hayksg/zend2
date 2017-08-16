<?php
namespace Tutorial\View\Helper;

use Zend\View\Helper\AbstractHelper;

class GetTime extends AbstractHelper
{
    public function __invoke()
    {
        $dt = new \DateTime('now', new \DateTimeZone('Asia/Yerevan'));
        $time = $dt->format('H:i:s');
        return $time ?: false;
    }
}
