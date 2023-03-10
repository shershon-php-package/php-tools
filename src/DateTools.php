<?php

namespace PhpTools;

use \DateTime;
use \Exception;

/**
 * @Description: 日期处理类
 * @Class DateTools
 * @Package PhpTools
 */
class DateTools
{
    /**
     * @description: 根据时分秒生成时间字符串
     * @param $hours
     * @param $minutes
     * @param $seconds
     * @return string
     * @autor Shershon
     */
    public static function hourGenerate($hours, $minutes, $seconds)
    {
        return implode(':', [$hours, $minutes, $seconds]);
    }

    /**
     * 一日之初
     *
     * @param $date
     *
     * @return string // $date 00:00:00
     */
    public static function dayBeginTime($date)
    {
        $tab = explode(' ', $date);
        if (!isset($tab[1])) {
            $date .= ' ' . self::hourGenerate('00', '00', '00');
        }

        return $date;
    }

    /**
     * 一日之终
     *
     * @param $date
     *
     * @return string  // $date  23:59:59
     */
    public static function dayEndTime($date)
    {
        $tab = explode(' ', $date);
        if (!isset($tab[1])) {
            $date .= ' ' . self::hourGenerate('23', '59', '59');
        }

        return $date;
    }


    /**
     * 返回毫秒数
     * @return int
     */
    public static function getMicroTime()
    {
        list($usec, $sec) = explode(" ", microtime());
        return floor((floatval($sec) + floatval($usec)) * 1000);
    }

    /**
     * 日期推送计算(最小单位是天)
     *
     * @param $interval
     * @param $step
     * @param $date
     *
     * @demo
     *       dateAdd('d', 1, $beginDate); //获取明天
     *
     * @return bool|string
     */
    public static function dateAdd($interval, $step, $date)
    {
        list($year, $month, $day) = explode('-', $date);
        $interval = strtolower($interval);
        if ($interval == 'y') {
            return date(
                'Y-m-d',
                mktime(0, 0, 0, $month, $day, intval($year) + intval($step))
            );
        } elseif ($interval == 'm') {
            return date(
                'Y-m-d',
                mktime(0, 0, 0, intval($month) + intval($step), $day, $year)
            );
        } elseif ($interval == 'd') {
            return date(
                'Y-m-d',
                mktime(0, 0, 0, $month, intval($day) + intval($step), $year)
            );
        }

        return date('Y-m-d');
    }

    /**
     *  Functional description : 计算两个时间相差:几年几月几日几时几分几秒
     *  Programmer : Mr.Liu
     *
     * @param $maxDate YYYY-mm-dd
     * @param $minDate YYYY-mm-dd
     *
     * @return mixed
     * @throws Exception
     */
    public static function DifferTime($maxDate, $minDate)
    {
        $maxDateTime = new DateTime($maxDate);
        $minDateTime = new DateTime($minDate);
        $interval = $maxDateTime->diff($minDateTime);
        $tmp['y'] = $interval->format('%Y');
        $tmp['m'] = $interval->format('%m');
        $tmp['d'] = $interval->format('%d');
        $tmp['H'] = $interval->format('%H');
        $tmp['i'] = $interval->format('%i');
        $tmp['s'] = $interval->format('%s');
        return $tmp;
    }

    /**
     * description: 获取当前日期所在的星期一和星期日
     * @param $date
     * @return array
     * @author: Shershon
     */
    public static function getWeekRange($date)
    {
        $timestamp = strtotime($date);
        $w = strftime('%u', $timestamp);
        $monday = date('Y-m-d', $timestamp - ($w - 1) * 86400);
        $sunday = date('Y-m-d', $timestamp + (7 - $w) * 86400);
        return [$monday, $sunday];
    }

    /**
     * description: 获取当前日期所在的月末和月初
     * @param $date
     * @return array
     * @author: Shershon
     */
    public static function getMonthRange($date)
    {
        $timestamp = strtotime($date);
        $firstDay = date('Y-m-01', $timestamp);
        $lastDay = date('Y-m-' . date('t', $timestamp), $timestamp);
        return [$firstDay, $lastDay];
    }

    /**
     * description: 美化时间
     * @param $time
     * @return false|string
     * @author: Shershon
     */
    public function beautyTime($time)
    {
        $todayLast = strtotime(date('Y-m-d') . ' 23:59:59');
        $agoTimeTrue = time() - $time;
        $agoTime = $todayLast - $time;
        $agoDay = floor($agoTime / 86400);
        $tomorrow = date('Y-m-d', strtotime("+1 day"));
        $timeDate = date('Y-m-d', $time);
        if ($agoTimeTrue < 60) {
            $res = '刚刚';
        } elseif ($agoTimeTrue < 3600) {
            $res = (ceil($agoTimeTrue / 60)) . '分钟前';
        } elseif ($agoTimeTrue < (3600 * 12)) {
            $res = (ceil($agoTimeTrue / 3600)) . '小时前';
        } elseif ($agoDay == 0) {
            $res = '今天 ' . date('H:i', $time);
        } elseif ($agoDay == 1) {
            $res = '昨天 ' . date('H:i', $time);
        } elseif ($agoDay == 2) {
            $res = '前天 ' . date('H:i', $time);
        } elseif (($agoDay > 2) && ($agoDay < 16)) {
            $res = $agoDay . '天前' . date('H:i', $time);
        } elseif ($tomorrow == $timeDate) {
            $res = '明天 ' . date('H:i', $time);
        } else {
            $res = date('Y-m-d H:i:s', $time);
        }
        return $res;
    }
}
