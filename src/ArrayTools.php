<?php

namespace PhpTools;

/**
 * @Description: 数组相关的操作
 * @Class ArrayTools
 * @Package PhpTools
 */
class ArrayTools
{

    /**
     * @description: 对象转数组
     * @param $object
     * @return mixed
     * @autor Shershon
     */
    public static function object2array($object)
    {
        return json_decode(json_encode($object), true);
    }


    /**
     * @description: xml转为数组
     * @param $xml
     * @return bool|mixed
     * @autor Shershon
     */
    public static function xml2Array($xml)
    {
        $result = json_decode(
            json_encode(simplexml_load_string(
                trim($xml, ' '),
                'SimpleXMLElement',
                LIBXML_NOCDATA
            )),
            true
        );
        if (!$result) {
            return false;
        }
        $isArray = false;
        foreach ($result as $_val) {
            if (is_array($_val)) {
                $isArray = true;
            }
            break;
        }
        if ($isArray) {
            $result = array_values($result);
            if (isset($result[0][0])) {
                $infos = $result[0];
            } else {
                $infos = $result;
            }
        } else {
            $infos[] = $result;
        }

        foreach ($infos as &$_val) {
            foreach ($_val as $key => &$item) {
                if (self::isEmpty($item)) {
                    $item = '';
                }
            }
        }
        return $infos;
    }

    /**
     * 一维数组转为二维
     *
     * @param array $array
     *
     * @return array
     */
    public static function arrayToArrays(array $array)
    {
        if (!is_array($array)) {
            return $array;
        }
        if (!isset($array[0])) {
            $arrays[] = $array;
        } else {
            $arrays = $array;
        }
        return $arrays;
    }

    /**
     * @description: 多维数组根据某个key,进行排序
     * @param $data
     * @param $sortKey
     * @param int $sortType // SORT_DESC SORT_ASC |SORT_DESC
     * @return mixed
     * @autor Shershon
     */
    public static function arraySortByKey($data, $sortKey, $sortType = SORT_DESC)
    {
        foreach ($data as $key => $row) {
            $sortKeyArray[$key] = $row[$sortKey];
        }
        array_multisort($sortKeyArray, $sortType);
        return $data;
    }

    /**
     * Desc: 多维数组，过滤指定key为空的元素
     * Author: Shershon(tanxiaoshan@weimiao.cn)
     * DateTime: 2021/12/31 上午11:42
     * @param $data
     * @param $key
     * @return array
     */
    public static function arrayFilterByEmptyKey($data, $key)
    {
        $ret = [];
        foreach ($data as $v1) {
            $tmp = [];
            foreach ($v1 as $v2) {
                if (!empty($v2[$key])) {
                    $tmp[] = $v2;
                }
            }
            /*array_map(function($v2) use(&$tmp, $key){
                if(!empty($v2[$key])) {
                    $tmp[] = $v2;
                }
            }, $v1);*/
            /*$tmp = array_filter($v1, function($v2) use($key){
                return !empty($v2[$key]);
            });*/
            !empty($tmp) && $ret[] = $tmp;
        }
        return $ret;
    }
}