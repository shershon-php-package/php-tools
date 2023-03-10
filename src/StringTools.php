<?php

namespace PhpTools;

/**
 * @Description: 字符串相关操作
 * @Class StringTools
 * @Package PhpTools
 */
class StringTools
{
    /**
     * @description: 计算字符串长度
     * @param $str
     * @param string $encoding
     * @return bool|int
     * @autor Shershon
     */
    public static function strlen(string $str, $encoding = 'UTF-8')
    {
        if (is_array($str) || is_object($str)) {
            return false;
        }
        $str = html_entity_decode($str, ENT_COMPAT, 'UTF-8');
        if (function_exists('mb_strlen')) {
            return mb_strlen($str, $encoding);
        }
        return strlen($str);
    }

    /**
     * 清除非法编码
     * @param $pattern
     * @return mixed
     */
    public static function cleanNonUnicodeSupport($pattern)
    {
        if (!defined('PREG_BAD_UTF8_OFFSET')) {
            return $pattern;
        }
        return preg_replace(
            '/\\\[px]\{[a-z]\}{1,2}|(\/[a-z]*)u([a-z]*)$/i',
            "$1$2",
            $pattern
        );
    }
}