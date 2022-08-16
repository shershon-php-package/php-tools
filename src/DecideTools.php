<?php

namespace phpTools;

/**
 * @Description: 判断相关的操作
 * @Class DecideTools
 * @Package phpTools
 */
class DecideTools
{
    /**
     * @description: 判断是否为空
     * @param $field
     * @return bool
     * @autor Mr.LiuQHui
     */
    public static function isEmpty($field)
    {
        if ($field === '' || $field === null) {
            return true;
        } else {
            if (is_array($field) && empty($field)) {
                return true;
            }
        }
        return false;
    }
}