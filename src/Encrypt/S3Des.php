<?php

namespace PhpTools\Encrypt;

/**
 * Description: 3des加解密
 * Trait S3Des
 * @package PhpTools\encrypt
 */
class S3Des
{
    /**
     * @description: 加密
     * @param $key
     * @param $input
     * @return string
     * @autor Shershon
     */
    public static function encrypt($key, $input)
    { // 数据加密
        $key  = str_pad($key, 24, '0');
        $data = openssl_encrypt($input, 'des-ede3', $key, OPENSSL_RAW_DATA);
        $data = base64_encode($data);
        return $data;
    }

    /**
     * @description: 解密
     * @param $key
     * @param $encrypted
     * @return bool|string
     * @autor Shershon
     */
    public static function decrypt($key, $encrypted)
    { // 数据解密
        $encrypted = base64_decode($encrypted);
        $key       = str_pad($key, 24, '0');
        $decrypted = openssl_decrypt($encrypted, 'des-ede3', $key, OPENSSL_RAW_DATA | OPENSSL_ZERO_PADDING, '');
        //return self::pkcs5_unpad($decrypted);
        return $decrypted;
    }

    /**
     * @param $text
     * @param $blocksize
     * @return string
     */
    private static function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    /**
     * @param $text
     * @return bool|string
     */
    private static function pkcs5_unpad($text)
    {
        $pad = ord($text[strlen($text) - 1]);
        if ($pad > strlen($text)) {
            return false;
        }
        if (strspn($text, chr($pad), strlen($text) - $pad) != $pad) {
            return false;
        }
        return substr($text, 0, -1 * $pad);
    }

    /**
     * @param $data
     * @return string
     */
    private function PaddingPKCS7($data)
    {
        return $data;
    }

}