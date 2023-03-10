<?php

namespace ToolsTest\Cases;

use PhpTools\DateTools;
use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    public function testSomething()
    {
        $date = '2020-02-22';
        $d    = DateTools::getMonthRange($date);
        $this->assertIsArray($d);
    }
}
