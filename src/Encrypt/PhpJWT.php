<?php

namespace PhpTools\Encrypt;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class PhpJWT
{
    public static function encode($payload, $key, $alg)
    {
        return JWT::encode($payload, $key, $alg);
    }

    public static function decode($jwt, $key, $alg)
    {
        return JWT::decode($jwt, new Key($key, $alg));
    }
}