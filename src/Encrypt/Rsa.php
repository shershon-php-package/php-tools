<?php

namespace PhpTools\Encrypt;

class Rsa
{

    public static function openssl_public_encrypt($str, &$encrypt, $publicKey)
    {
        openssl_public_encrypt($str, $encrypt, $publicKey);
    }

    public static function openssl_private_decrypt($query, &$decrypt, $privateKey)
    {
        openssl_private_decrypt($query, $decrypt, $privateKey);
    }
}