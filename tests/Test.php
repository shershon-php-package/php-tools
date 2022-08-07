<?php

use phpTools\DateTools;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testSomething()
    {
        $date = '2020-02-22';
        $d    = DateTools::getMonthRange($date);
        $a    = getcwd();
        var_dump($a, $d);
    }
}
