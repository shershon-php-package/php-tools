<?php

// 循环迭代
function getTree($items)
{
    $tree = [];
    foreach ($items as $key => $item) {
        if (isset($items[$item['pid']])) {
            $items[$item['pid']]['child'][] = &$items[$key];
        } else {
            $tree[] = &$items[$key];
        }
    }
    return $tree;
}

// 递归
function getTree2($arr, $pid = 0, $level = 0)
{
    static $list = [];
    foreach ($arr as $k => $v) {
        // 判断子类的父级id 是否等于 父级的id
        if ($v['pid'] == $pid) {
            $v['level'] = $level;
            $list[]     = $v;
            unset($arr[$k]); // 删除已排好的数据，减少循环次数
            getTree($arr, $v['id'], $level + 1);
        }
    }
    return $list;
}

$arr = [
    1 => ['id' => 1, 'pid' => 0, 'cname' => '人类'],
    2 => ['id' => 2, 'pid' => 1, 'cname' => '男人'],
    3 => ['id' => 3, 'pid' => 1, 'cname' => '女人'],
    4 => ['id' => 4, 'pid' => 0, 'cname' => '水果'],
    5 => ['id' => 5, 'pid' => 4, 'cname' => '苹果'],
    6 => ['id' => 6, 'pid' => 4, 'cname' => '梨子'],
    7 => ['id' => 7, 'pid' => 5, 'cname' => '红苹果'],
];

$rst = getTree($arr);
print_r($rst) . PHP_EOL;

$rst2 = getTree2($arr);
foreach ($rst2 as $k => $v) {
    echo str_repeat("|----", $v['level']) . $v['cname'] . PHP_EOL;
}