<?php

namespace PhpTools\Encrypt;

class Aes
{
    const AES_ECB = 'ECB';
    const AES_CBC = 'CBC';

    /**
     * 作者: Zengzhong (74039@qq.com)
     * 日期: 2019-02-10 19:29
     * 功能：
     * @param $input
     * @param $key
     * @param $mode
     * @param $iv
     * @return string
     */
    public static function openssl_encrypt($input, $key, $mode, $iv = "")
    {
        $data = openssl_encrypt($input, $mode, $key, OPENSSL_RAW_DATA, $iv);
        $data = base64_encode($data);

        return $data;
    }


    /**
     * @description: 加密
     * @param $key
     * @param $input
     * @param string $modeType
     * @param string $iv
     * @return string
     * @autor Shershon
     */
    public static function encrypt($key, $input, $modeType = self::AES_ECB, $iv = "")
    {
        $mode = self::getMode(strlen($key), $modeType);
        if ($mode) {
            return self::openssl_encrypt($input, $key, $mode, $iv);
        }
        return '';
    }

    /**
     * @description: 解密
     * @param $key
     * @param $sStr
     * @param string $modeType
     * @param string $iv
     * @return false|string
     * @autor Shershon
     */
    public static function decrypt($key, $sStr, $modeType = self::AES_ECB, $iv = "")
    {
        $mode = self::getMode(strlen($key), $modeType);
        if ($mode) {
            $decrypted =
                openssl_decrypt(base64_decode($sStr), $mode, $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, $iv);
        } else {
            $decrypted = '';
        }
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s - 1]);
        $decrypted = substr($decrypted, 0, -$padding);

        return $decrypted;
    }


    /**
     * @description: 获取加解密的Mode，KEY的长度不同会影响到OpenSSL中aes-x-cbc是选择128，192还是256。
     * @param $lenKey
     * @param $modeType
     * @return string
     * @autor Shershon
     */
    public static function getMode($lenKey, $modeType)
    {
        $mode = '';
        if ($modeType == self::AES_ECB) {
            if ($lenKey == 16) {
                $mode = 'AES-128-ECB';
            } else {
                if ($lenKey == 24) {
                    $mode = 'AES-192-ECB';
                } else {
                    if ($lenKey == 32) {
                        $mode = 'AES-256-ECB';
                    }
                }
            }
        } else {
            if ($lenKey == 16) {
                $mode = 'AES-128-CBC';
            } else {
                if ($lenKey == 24) {
                    $mode = 'AES-192-CBC';
                } else {
                    if ($lenKey == 32) {
                        $mode = 'AES-256-CBC';
                    }
                }
            }
        }
        return $mode;
    }

    /**
     * @param $text
     * @param $blockSize
     * @return string
     */
    public static function PKCS5Padding($text, $blockSize)
    {
        $pad = $blockSize - (strlen($text) % $blockSize);

        return $text . str_repeat(chr($pad), $pad);
    }

}