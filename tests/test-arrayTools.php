<?php

require_once '../vendor/autoload.php';

use phpTools\ArrayTools;

$json   = '[[{"id":17876,"is_new":1,"lesson_id":2857,"child_id":2858},{"id":17876,"is_new":1,"lesson_id":2857,"child_id":2858}],[{"id":17877,"is_new":1,"lesson_id":2859,"child_id":2862}],[{"id":17878,"is_new":1,"lesson_id":2860,"child_id":2863}],[{"id":17879,"is_new":1,"lesson_id":2861,"child_id":2864}],[{"id":17951,"is_new":1,"lesson_id":0,"child_id":0}]]';
$arr    = json_decode($json, true);
$arrNew = ArrayTools::arrayFilterByEmptyKey($arr, 'lesson_id');
// print_r($arrNew);

/**
 * array_push/array_pop/array_unshift/array_shift
 */
$arr2 = [];
array_push($arr2, 4, 5, 6);
echo implode("-", $arr2) . PHP_EOL;
array_pop($arr2);
echo implode("-", $arr2) . PHP_EOL;
array_unshift($arr2, 1, 2, 3);
echo implode("-", $arr2) . PHP_EOL;
array_shift($arr2);
echo implode("-", $arr2) . PHP_EOL;
/*输出
4-5-6
4-5
1-2-3-4-5
2-3-4-5
 */