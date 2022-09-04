<?php
/**
 * @Description:
 * @Author: Shershon
 * @Date: 2020/7/17 3:03 下午
 */


namespace PhpTools;


/**
 * @Description: 验证类相关操作
 * @Class ValidateTools
 * @Package PhpTools
 */
class ValidateTools
{
    /**
     * 是否是邮箱地址
     * @param $email
     * @return bool
     */
    function is_email($email)
    {
        return filter_var($email, FILTER_VALIDATE_EMAIL) ? true : false;
    }

    /**
     * 校验是否是手机号
     *
     * @param $mobilePhone
     *
     * @return int
     */
    public static function isMobilePhone($mobilePhone)
    {
        return preg_match("/^1[23456789][0-9]{9}$/", $mobilePhone);
    }

    /**
     * 校验是否是中文
     *
     * @param $data
     *
     * @return int
     */
    public static function isChinese($data)
    {
        return preg_match("/^[\x{4e00}-\x{9fa5}a-zA-Z_]+$/u", $data);
    }

    /**
     * 校验是否是md5
     *
     * @param $md5
     *
     * @return int
     */
    public static function isMd5($md5)
    {
        return preg_match('/^[a-f0-9A-F]{32}$/', $md5);
    }

    /**
     * 校验是否是Sha1
     *
     * @param $sha1
     *
     * @return int
     */
    public static function isSha1($sha1)
    {
        return preg_match('/^[a-fA-F0-9]{40}$/', $sha1);
    }

    /**
     * 校验是否是token
     *
     * @param $token
     *
     * @return int
     */
    public static function isToken($token)
    {
        return preg_match('/^[a-zA-Z0-9=]+$/', $token);
    }

    /**
     * 校验是否是float
     *
     * @param $float
     *
     * @return bool
     */
    public static function isFloat($float)
    {
        return strval((float)$float) == strval($float);
    }

    /**
     * 校验是否是大于0的float
     *
     * @param $float
     *
     * @return bool
     */
    public static function isUnsignedFloat($float)
    {
        return strval((float)$float) == strval($float) && $float >= 0;
    }


    /**
     * 校验是否是名称
     *
     * @param $name
     *
     * @return int
     */
    public static function isName($name)
    {
        return preg_match(Str::cleanNonUnicodeSupport('/^[^!<>,;?=+()@#"°{}$%:]+$/u'),
            stripslashes($name));
    }


    /**
     * 是否是干净的html源码
     *
     * @param $html
     *
     * @return bool
     */
    public static function isCleanHtml($html)
    {
        $events = 'onmousedown|onmousemove|onmmouseup|onmouseover|onmouseout|onload|onunload|onfocus|onblur|onchange';
        $events .= '|onsubmit|ondblclick|onclick|onkeydown|onkeyup|onkeypress|onmouseenter|onmouseleave|onerror|onselect|onreset|onabort|ondragdrop|onresize|onactivate|onafterprint|onmoveend';
        $events .= '|onafterupdate|onbeforeactivate|onbeforecopy|onbeforecut|onbeforedeactivate|onbeforeeditfocus|onbeforepaste|onbeforeprint|onbeforeunload|onbeforeupdate|onmove';
        $events .= '|onbounce|oncellchange|oncontextmenu|oncontrolselect|oncopy|oncut|ondataavailable|ondatasetchanged|ondatasetcomplete|ondeactivate|ondrag|ondragend|ondragenter|onmousewheel';
        $events .= '|ondragleave|ondragover|ondragstart|ondrop|onerrorupdate|onfilterchange|onfinish|onfocusin|onfocusout|onhashchange|onhelp|oninput|onlosecapture|onmessage|onmouseup|onmovestart';
        $events .= '|onoffline|ononline|onpaste|onpropertychange|onreadystatechange|onresizeend|onresizestart|onrowenter|onrowexit|onrowsdelete|onrowsinserted|onscroll|onsearch|onselectionchange';
        $events .= '|onselectstart|onstart|onstop';

        return (!preg_match('/<[ \t\n]*script/ims',
                $html) && !preg_match('/(' . $events . ')[ \t\n]*=/ims',
                $html) && !preg_match('/.*script\:/ims',
                $html) && !preg_match('/<[ \t\n]*i?frame/ims', $html));
    }

    /**
     * 校验是否是密码
     *
     * @param     $passwd
     * @param int $size //密码长度
     *
     * @return int
     */
    public static function isPasswd($passwd, $size = 6)
    {
        return preg_match('/^[.a-z_0-9-!@#$%\^&*()]{' . $size . ',32}$/ui',
            $passwd);
    }

    /**
     * 校验是否是日期
     *
     * @param $date
     *
     * @return bool
     */
    public static function isDate($date)
    {

        $time = strtotime($date);
        if ($time === false) {
            return false;
        }
        $datetime = date('Y-m-d H:i:s', $time);
        return (bool)preg_match('/^([0-9]{4})-((0?[0-9])|(1[0-2]))-((0?[0-9])|([1-2][0-9])|(3[01]))( [0-9]{2}:[0-9]{2}:[0-9]{2})?$/',
            $datetime);
    }

    /**
     * 校验是否是时间戳
     *
     * @param $time
     *
     * @return bool
     */
    public static function isTimestamp($time)
    {
        return (int)$time > 0 && strtotime(date('Y-m-d H:i:s',
                $time)) === (int)$time;
    }

    /**
     * 校验是否是生日
     *
     * @param $date
     *
     * @return bool
     */
    public static function isBirthDate($date)
    {
        if (!self::isDate($date)) {
            return false;
        }
        $date = date('Y-m-d', strtotime($date));
        if (empty($date) || $date == '0000-00-00') {
            return true;
        }
        if (preg_match('/^([0-9]{4})-((?:0?[1-9])|(?:1[0-2]))-((?:0?[1-9])|(?:[1-2][0-9])|(?:3[01]))([0-9]{2}:[0-9]{2}:[0-9]{2})?$/',
            $date, $birth_date)) {
            if ($birth_date[1] > date('Y') && $birth_date[2] > date('m') && $birth_date[3] > date('d')) {
                return false;
            }

            return true;
        }

        return false;
    }


    /**
     *  校验是否是url
     * @param $url
     *
     * @return int
     */
    public static function isUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) ? true : false;
    }

    /**
     * 校验是否是字符串
     * @param $data
     *
     * @return bool
     */
    public static function isString($data)
    {
        return !empty($data) && is_string($data);
    }


    /**
     * 校验是否是IP
     * @param $ip
     *
     * @return bool
     */
    public static function isIp($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4 | FILTER_FLAG_IPV6) ? true : false;
    }

    /**
     * 校验是否是数字
     * @param $data
     *
     * @return int
     */
    public static function isNumber($data)
    {
        return preg_match("/^-?[0-9]+$/u", $data);
    }


    /**
     * 护照号
     *
     * @param $code
     *
     * @return bool
     */
    public static function isPassportID($code)
    {
        if (preg_match('/^(G|S|D|14|15|P\.|S\.|D\.)\d{7,8}$/', $code)) {
            return true;
        }
        return false;
    }


    /**
     * 检查是否是身份证
     *
     * @param $id_card
     *
     * @return bool
     */
    public static function isIdCard($id_card)
    {
        if (strlen($id_card) == 18) {
            return self::idcard_checksum18($id_card);
        } elseif ((strlen($id_card) == 15)) {
            $id_card = self::idcard_15to18($id_card);

            return self::idcard_checksum18($id_card);
        } else {
            return false;
        }
    }

    /**
     * 18位身份证校验码有效性检查
     *
     * @param $idcard
     *
     * @return bool
     */
    protected static function idcard_checksum18($idcard)
    {
        if (strlen($idcard) != 18) {
            return false;
        }
        $idcard_base = substr($idcard, 0, 17);
        if (self::idcard_verify_number($idcard_base) != strtoupper(substr($idcard,
                17, 1))
        ) {
            return false;
        } else {
            return true;
        }
    }


    /**
     * 计算身份证校验码，根据国家标准GB 11643-1999
     *
     * @param $idcard_base
     *
     * @return mixed
     */
    protected static function idcard_verify_number($idcard_base)
    {
        if (strlen($idcard_base) != 17) {
            return false;
        }
        // 校验生日
        $birthday = substr($idcard_base, 6, 8);
        if (strtotime($birthday) === false) {
            // 230603199407361234 这个身份证就能校验通过，但是生日不正确
            return false;
        }
        //加权因子
        $factor = [7, 9, 10, 5, 8, 4, 2, 1, 6, 3, 7, 9, 10, 5, 8, 4, 2];
        //校验码对应值
        $verify_number_list = [
            '1',
            '0',
            'X',
            '9',
            '8',
            '7',
            '6',
            '5',
            '4',
            '3',
            '2'
        ];
        $checksum = 0;
        for ($i = 0; $i < strlen($idcard_base); $i++) {
            $checksum += substr($idcard_base, $i, 1) * $factor[$i];
        }
        $mod = $checksum % 11;
        $verify_number = $verify_number_list[$mod];

        return $verify_number;
    }

    /**
     * 将15位身份证升级到18位
     *
     * @param $idcard
     *
     * @return string
     */
    protected static function idcard_15to18($idcard)
    {
        if (strlen($idcard) != 15) {
            return false;
        } else {
            // 如果身份证顺序码是996 997 998 999，这些是为百岁以上老人的特殊编码
            if (array_search(substr($idcard, 12, 3),
                    ['996', '997', '998', '999']) !== false
            ) {
                $idcard = substr($idcard, 0, 6) . '18' . substr($idcard, 6, 9);
            } else {
                $idcard = substr($idcard, 0, 6) . '19' . substr($idcard, 6, 9);
            }
        }
        $idcard = $idcard . self::idcard_verify_number($idcard);
        return $idcard;
    }

    /**
     *  Functional description : 解析正确就返回解析结果,否则返回false,说明字符串不是XML格式
     *
     * @param $str
     *
     * @return bool|mixed
     */
    public static function isXml($str)
    {
        $xml_parser = xml_parser_create();
        if (!xml_parse($xml_parser, $str, true)) {
            xml_parser_free($xml_parser);
            return false;
        } else {
            return (json_decode(json_encode(simplexml_load_string($str)),
                true));
        }
    }

    /**
     * 正则验证密码强度（小于6位，强度为1）
     * 密码字符包括：小写字母、大写字母、数字、符号等
     * @param string $pass 密码
     * @return number 可能为：无0、弱1、中2、3强、4很强
     */
    public static function checkPwdSecurity($pass)
    {
        $pattern = '/^(?:([a-z])|([A-Z])|([0-9])|(.)){6,}|(.)+$/';
        $replacement = '$1$2$3$4$5';
        return strlen(preg_replace($pattern, $replacement, $pass));
    }

    /**
     * description: 判断是否是合法的图片url
     * @param $imgUrl
     * @return bool
     * @author: Shershon
     */
    public static function isImgUrl($imgUrl)
    {
        if (!ValidateTools::isUrl($imgUrl)) {
            return false;
        }
        $imgInfo = get_headers($imgUrl, 1);
        if (strpos($imgInfo[0], '200 OK') !== false && strpos($imgInfo['Content-Type'], 'image') !== false) {
            return true;
        }
        return false;
    }


}
