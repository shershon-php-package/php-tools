<?php

namespace PhpTools;

use \Exception;

/**
 * @Description: 与http请求相关的操作
 * @Class HttpTools
 * @Package PhpTools
 */
class HttpTools
{
    /**
     * 获取用户IP地址
     * @return mixed
     */
    public static function getRemoteAddr()
    {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])
            && $_SERVER['HTTP_X_FORWARDED_FOR']
            && (!isset($_SERVER['REMOTE_ADDR'])
                || preg_match(
                    '/^127\..*/i',
                    trim($_SERVER['REMOTE_ADDR'])
                )
                || preg_match('/^172\.16.*/i', trim($_SERVER['REMOTE_ADDR']))
                || preg_match('/^192\.168\.*/i', trim($_SERVER['REMOTE_ADDR']))
                || preg_match('/^10\..*/i', trim($_SERVER['REMOTE_ADDR'])))
        ) {
            if (strpos($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
                $ips = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

                return $ips[0];
            } else {
                return $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
        }
        return $_SERVER['REMOTE_ADDR'];
    }

    /**
     * @description: 获取国内随机IP地址(IPv4)
     * 注：适用于32位操作系统
     * @return string
     * @autor Shershon
     */
    public static function randIp()
    {
        $ip_long = [
            ['607649792', '608174079'], //36.56.0.0-36.63.255.255
            ['1038614528', '1039007743'], //61.232.0.0-61.237.255.255
            ['1783627776', '1784676351'], //106.80.0.0-106.95.255.255
            ['2035023872', '2035154943'], //121.76.0.0-121.77.255.255
            ['2078801920', '2079064063'], //123.232.0.0-123.235.255.255
            ['-1950089216', '-1948778497'], //139.196.0.0-139.215.255.255
            ['-1425539072', '-1425014785'], //171.8.0.0-171.15.255.255
            ['-1236271104', '-1235419137'], //182.80.0.0-182.92.255.255
            ['-770113536', '-768606209'], //210.25.0.0-210.47.255.255
            ['-569376768', '-564133889'], //222.16.0.0-222.95.255.255
        ];
        $rand_key = mt_rand(0, 9);
        return long2ip(mt_rand($ip_long[$rand_key][0], $ip_long[$rand_key][1]));
    }


    /**
     * @description: 并发请求
     * @param $requestList
     * $requestList=[
     * ['url'=>'','method'=>'POST','param'=>[]],
     * ['url'=>'','method'=>'GET','param'=>[]]
     * ];
     * @param null $proxy
     * @param null $proxyPort
     * @param null $header
     * @param int $timeout
     * @return array
     * @autor Shershon
     */
    public static function multiCurl(
        $requestList,
        $proxy = null,
        $proxyPort = null,
        $header = null,
        $timeout = 600
    )
    {
        $result = [];

        if (!is_array($requestList) || count($requestList) == 0) {
            return $result;
        }
        $handles = static::createHandle(
            $requestList,
            $proxy,
            $proxyPort,
            $header,
            $timeout
        );
        $mh = curl_multi_init();
        foreach ($handles as $handle) {
            curl_multi_add_handle($mh, $handle);
        }
        do {
            curl_multi_exec($mh, $running);
            curl_multi_select($mh);
        } while ($running > 0);

        foreach ($handles as $key => $handle) {
            $result[$key] = curl_multi_getcontent($handle);
            curl_multi_remove_handle($mh, $handle);
        }
        curl_multi_close($mh);
        return $result;
    }


    /**
     * @param      $requestList
     * @param null $proxy
     * @param null $proxyPort
     * @param null $header $header = array('Content-type: application/json');
     * @param int $timeout
     *
     * @return array
     */
    protected static function createHandle(
        $requestList,
        $proxy = null,
        $proxyPort = null,
        $header = null,
        $timeout = 600
    )
    {
        $handles = [];
        foreach ($requestList as $key => $request) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_FAILONERROR, false);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
            if ($proxy) {
                curl_setopt($ch, CURLOPT_PROXY, $proxy);
                curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
            }
            if (strlen($request['url']) > 5 && strtolower(substr(
                    $request['url'],
                    0,
                    5
                )) == "https"
            ) {
                /** CURLOPT_SSL_VERIFYPEER php7.1 之后才有*/
                if (substr(PHP_VERSION, 0, 3) >= '7.1') {
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                }
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            }
            switch (strtoupper($request['method'])) {
                case 'POST':
                    curl_setopt($ch, CURLOPT_POST, true);
                    if (!empty($request['param'])) {
                        if (is_array($request['param']) || is_object($request['param'])) {
                            if (is_object($request['param'])) {
                                $postFields = ArrayTools::object2array($request['param']);
                            }
                            $postBodyString = "";
                            $postMultipart = false;
                            foreach ($request['param'] as $k => $v) {
                                if ("@" != substr($v, 0, 1)) { //判断是不是文件上传
                                    $postBodyString .= "$k=" . urlencode($v) . "&";
                                } else { //文件上传用multipart/form-data，否则用www-form-urlencoded
                                    $postMultipart = true;
                                }
                            }
                            unset($k, $v);
                            if ($postMultipart) {
                                curl_setopt(
                                    $ch,
                                    CURLOPT_POSTFIELDS,
                                    $request['param']
                                );
                            } else {
                                curl_setopt(
                                    $ch,
                                    CURLOPT_POSTFIELDS,
                                    substr($postBodyString, 0, -1)
                                );
                            }
                        } else {
                            curl_setopt(
                                $ch,
                                CURLOPT_POSTFIELDS,
                                $request['param']
                            );
                        }
                    }
                    break;
                default:
                    if (!empty($postFields) && is_array($postFields)) {
                        $request['url'] .= (strpos(
                                $request['url'],
                                '?'
                            ) === false ? '?' : '&')
                            . http_build_query($postFields);
                    }
                    break;
            }
            curl_setopt($ch, CURLOPT_URL, $request['url']);

            if (!empty($header) && is_array($header)) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
            }
            $handles[$key] = $ch;
        }

        return $handles;
    }


    /**
     * 网络请求
     * @param        $url
     * @param string $method
     * @param null $postFields
     * @param null $header
     * @param null $proxy
     * @param null $proxyPort
     *
     * @return mixed
     * @throws Exception
     */
    public static function curl(
        $url,
        $method = 'GET',
        $postFields = null,
        $proxy = null,
        $proxyPort = null,
        $header = null
    )
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_FAILONERROR, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        if ($proxy) {
            curl_setopt($ch, CURLOPT_PROXY, $proxy);
            curl_setopt($ch, CURLOPT_PROXYPORT, $proxyPort);
        }
        if (strlen($url) > 5 && strtolower(substr($url, 0, 5)) == "https") {
            /** CURLOPT_SSL_VERIFYPEER php7.1 之后才有*/
            if (substr(PHP_VERSION, 0, 3) >= '7.1') {
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            }
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        }

        switch (strtoupper($method)) {
            case 'POST':
                curl_setopt($ch, CURLOPT_POST, true);
                if (!empty($postFields)) {
                    if (is_array($postFields) || is_object($postFields)) {
                        if (is_object($postFields)) {
                            $postFields = ArrayTools::object2array($postFields);
                        }
                        $postBodyString = "";
                        $postMultipart = false;
                        foreach ($postFields as $k => $v) {
                            if ("@" != substr($v, 0, 1)) { //判断是不是文件上传
                                $postBodyString .= "$k=" . urlencode($v) . "&";
                            } else { //文件上传用multipart/form-data，否则用www-form-urlencoded
                                $postMultipart = true;
                            }
                        }
                        unset($k, $v);
                        if ($postMultipart) {
                            curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                        } else {
                            curl_setopt(
                                $ch,
                                CURLOPT_POSTFIELDS,
                                substr($postBodyString, 0, -1)
                            );
                        }
                    } else {
                        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);
                    }
                }
                break;
            default:
                if (!empty($postFields) && is_array($postFields)) {
                    $url .= (strpos($url, '?') === false ? '?' : '&')
                        . http_build_query($postFields);
                }
                break;
        }
        curl_setopt($ch, CURLOPT_URL, $url);

        if (!empty($header) && is_array($header)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        }
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception(curl_error($ch), 0);
        }
        curl_close($ch);

        return $response;
    }


    /**
     * description: 发起Post请求
     * @param $url
     * @param null $postFields ['key'=>val]
     * @param null $header array('Content-type: application/json');
     * @param null $proxy
     * @param null $proxyPort
     * @return mixed
     * @throws Exception
     * @author: Shershon
     */
    public static function post($url, $postFields = null, $header = null, $proxy = null, $proxyPort = null)
    {
        return self::curl($url, 'POST', $postFields, $proxy, $proxyPort, $header);
    }

    /**
     * description: 发起Get请求
     * @param $url
     * @param null $postFields ['key'=>val]
     * @param null $header array('Content-type: application/json');
     * @param null $proxy
     * @param null $proxyPort
     * @return mixed
     * @throws Exception
     * @author: Shershon
     */
    public static function get($url, $postFields = null, $header = null, $proxy = null, $proxyPort = null)
    {
        return self::curl($url, 'GET', $postFields, $proxy, $proxyPort, $header);
    }
}
