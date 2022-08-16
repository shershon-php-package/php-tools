<?php

namespace phpTools;

/**
 * @Description: 计算相关的操作
 * @Class ComputeTools
 * @Package phpTools
 */
class ComputeTools
{
    /**
     * @description: 计算机存储单位 转换
     * @param $value
     * @return int|string
     * @autor Mr.LiuQHui
     */
    public static function convertBytes($value)
    {
        if (is_numeric($value)) {
            return $value;
        } else {
            $value_length = strlen($value);
            $qty          = (int)substr($value, 0, $value_length - 1);
            $unit         = strtolower(substr($value, $value_length - 1));
            switch ($unit) {
                case 'k':
                    $qty *= 1024;
                    break;
                case 'm':
                    $qty *= 1048576;
                    break;
                case 'g':
                    $qty *= 1073741824;
                    break;
            }

            return $qty;
        }
    }
}