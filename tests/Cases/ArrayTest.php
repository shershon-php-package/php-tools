<?php

namespace ToolsTest\Cases;

use PhpTools\ArrayTools;
use PHPUnit\Framework\TestCase;

class ArrayTest extends TestCase
{
    public function testArrayFilterByEmptyKey()
    {
        $json   = '[[{"id":17876,"is_new":1,"lesson_id":2857,"child_id":2858},{"id":17876,"is_new":1,"lesson_id":2857,"child_id":2858}],[{"id":17877,"is_new":1,"lesson_id":2859,"child_id":2862}],[{"id":17878,"is_new":1,"lesson_id":2860,"child_id":2863}],[{"id":17879,"is_new":1,"lesson_id":2861,"child_id":2864}],[{"id":17951,"is_new":1,"lesson_id":0,"child_id":0}]]';
        $arr    = json_decode($json, true);
        $arrNew = ArrayTools::arrayFilterByEmptyKey($arr, 'lesson_id');
        $this->assertIsArray($arrNew);
    }

    // array_push/array_pop/array_unshift/array_shift
    public function testArrayQueue()
    {
        $arr = [];
        array_push($arr, 4, 5, 6);
        $this->assertEquals('4-5-6', implode("-", $arr));
        array_pop($arr);
        $this->assertEquals('4-5', implode("-", $arr));
        array_unshift($arr, 1, 2, 3);
        $this->assertEquals('1-2-3-4-5', implode("-", $arr));
        array_shift($arr);
        $this->assertEquals('2-3-4-5', implode("-", $arr));
    }
}