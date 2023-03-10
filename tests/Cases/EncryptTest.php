<?php

namespace ToolsTest\Cases;

use PHPUnit\Framework\TestCase;
use PhpTools\EncryptTools;

class EncryptTest extends TestCase
{
    /**
     * Desc: 对称加密-3DES
     * Author: Shershon
     * DateTime: 2022/8/16 22:50
     * @return void
     */
    public function test3DES()
    {
        // 3des加解密，key要24位，不够的话程序会自动补零
        $s3desKey            = 'aaaassssbbbggggjjjkkkeee';
        $data                = 'hello word';
        $result['原文']        = $data;
        $result['3des-秘钥长度'] = strlen($s3desKey);
        $result['3des-加密结果'] = $encryFor3des = EncryptTools::encrypt($data, EncryptTools::STD3DES, $s3desKey);
        $result['3des-解密结果'] = EncryptTools::decrypt($encryFor3des, EncryptTools::STD3DES, $s3desKey);
        $this->assertIsArray($result);
    }

    /**
     * Desc: 对称加密-AES
     * Author: Shershon
     * DateTime: 2022/8/16 22:50
     * @return void
     */
    public function testAES()
    {
        $aesKey             = 'aabbaabbaabbaabb';
        $data               = 'hello word';
        $encryForAes        = EncryptTools::encrypt($data, EncryptTools::AES, $aesKey);
        $result['aes-加密结果'] = $encryForAes;
        $result['aes-解密结果'] = EncryptTools::decrypt($encryForAes, EncryptTools::AES, $aesKey);
        $this->assertIsArray($result);
    }

    /**
     * Desc: 非对称加密-RSA
     * Author: Shershon
     * DateTime: 2022/8/16 22:53
     * @return void
     */
    public function testRSA()
    {
        // 公钥
        $publicKey = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQCuc3nGxe7Zv2racceAs47TvGZo
B6EA5tIvmz0wIufCj0K6nuZVHss1/SIXEtT4nmr+Zj7yzaaZDT3LNd5Tjzs1G92E
WLl28uI2r/ckk58OxEkLfmtcJKpwB+CMWqLwzeGnpmRC4KYrf+cXjTKc+UXNBQZZ
I8LpLEo1nt6zTKfBIwIDAQAB
-----END PUBLIC KEY-----';
        // 私钥
        $privateKey         = '-----BEGIN PRIVATE KEY-----
MIICdwIBADANBgkqhkiG9w0BAQEFAASCAmEwggJdAgEAAoGBAK5zecbF7tm/atpx
x4CzjtO8ZmgHoQDm0i+bPTAi58KPQrqe5lUeyzX9IhcS1Pieav5mPvLNppkNPcs1
3lOPOzUb3YRYuXby4jav9ySTnw7ESQt+a1wkqnAH4IxaovDN4aemZELgpit/5xeN
Mpz5Rc0FBlkjwuksSjWe3rNMp8EjAgMBAAECgYEArP4MC4YqZjnAn2BnAwSMJQHV
12GBUmCSm+zoj3x9sNzZwjBinpRL1XzwukrNcMG/vgjscWBnzaxo08PWdaw6e7u0
26z+FJeeIZ6BiTjWDP1J7HPrfCOQGbDm6d4DoSn0JfX47cD/KXSpQpEP0eOz6yxX
eRJ2VQgDCBT+5whWBCECQQDXMNjXYwsymo3z/69py9r1B1In++OlJDiSH+mv0+M/
fAxQYIQh4ggPdHJ6M1t4LYWT2gijQCzpNTNzAiMopQyzAkEAz4jIcVFvg59T0eQm
La8tAWDoOcdNG8PR1ZrXJACDDuHU2suR/TWlhZMSIbNJOhK3rr03tJedYcF8nXZP
oO2R0QJBAJ4FktboNorckC2Dr06jkpCo5Z3TDWJx7NDxemvRz2kJMQm9NoqjL4QZ
4Q73s83Wr+bZD8rCD7jZhoSIJ0Vrnp0CQCQg6swXYjNmvD/Q2PihA1O3HBZa5MiN
mWz3LLbew/IGTHjecYbEHRGY3dIyFPBgK8vmstjkgAhxl5EN9KTOVtECQGPQhaNj
LMGh+3IzuMiFUFWr4t0uLBqp4ISEcB3E1bMfctdK3VXgsbJbAGzwj+AuWMApUy4X
3OwheU/1Lk2dEB8=
-----END PRIVATE KEY-----
';
        $secretKey          = 'secret_key';
        $url                = 'http://127.0.0.1/encrypt/server.php?';
        $params['appKey']   = 'app_key';
        $params['orderId']  = 100;
        $params['name']     = 'zhangsan';
        $params['password'] = '123456';
        $params['time']     = time();
        $queryString        = http_build_query($params);
        // 生成摘要
        $sign        = EncryptTools::getSign($params, $secretKey);
        $queryString .= "&sign=" . $sign;
        $encrypt     = ''; // 密文
        EncryptTools::rsaEncrypt($queryString, $encrypt, $publicKey);
        $encrypt  = urlencode($encrypt);
        $url      .= "q=" . $encrypt;
        $decrypt  = ''; // 明文
        $queryStr = parse_url($url)['query'];
        parse_str($queryStr, $queryArr);
        EncryptTools::rsaDecrypt($queryArr['q'], $decrypt, $privateKey);
        parse_str($decrypt, $params);
        $paramSign = $params['sign'];
        unset($params['sign']);
        $sign = EncryptTools::getSign($params, $secretKey);
        // 对比摘要
        $this->assertEquals($paramSign, $sign);
    }

    /**
     * JWT,加密方式是HS系列
     * HS256,HS384,HS512
     *
     * @return void
     */
    public function testJWTForHs()
    {
        $payload              = [
            "issuer"     => "shershon",
            "issuer_at"  => time(),
            "expires_at" => time() + 60
        ];
        $key                  = 'shershon';
        $encryt               = EncryptTools::jwtEncode($payload, $key, 'HS256');
        $result['hs256-加密结果'] = $encryt;
        $result['hs256-解密结果'] = (array)EncryptTools::jwtDecode($encryt, $key, 'HS256');
        print_r($result);
    }

    /**
     * JWT,加密方式是RS系列
     * RS256,RS384,RS512
     *
     * @return void
     */
    public function testJWTForRs()
    {
        $privateKey = <<<EOD
-----BEGIN RSA PRIVATE KEY-----
MIIEowIBAAKCAQEAuzWHNM5f+amCjQztc5QTfJfzCC5J4nuW+L/aOxZ4f8J3Frew
M2c/dufrnmedsApb0By7WhaHlcqCh/ScAPyJhzkPYLae7bTVro3hok0zDITR8F6S
JGL42JAEUk+ILkPI+DONM0+3vzk6Kvfe548tu4czCuqU8BGVOlnp6IqBHhAswNMM
78pos/2z0CjPM4tbeXqSTTbNkXRboxjU29vSopcT51koWOgiTf3C7nJUoMWZHZI5
HqnIhPAG9yv8HAgNk6CMk2CadVHDo4IxjxTzTTqo1SCSH2pooJl9O8at6kkRYsrZ
WwsKlOFE2LUce7ObnXsYihStBUDoeBQlGG/BwQIDAQABAoIBAFtGaOqNKGwggn9k
6yzr6GhZ6Wt2rh1Xpq8XUz514UBhPxD7dFRLpbzCrLVpzY80LbmVGJ9+1pJozyWc
VKeCeUdNwbqkr240Oe7GTFmGjDoxU+5/HX/SJYPpC8JZ9oqgEA87iz+WQX9hVoP2
oF6EB4ckDvXmk8FMwVZW2l2/kd5mrEVbDaXKxhvUDf52iVD+sGIlTif7mBgR99/b
c3qiCnxCMmfYUnT2eh7Vv2LhCR/G9S6C3R4lA71rEyiU3KgsGfg0d82/XWXbegJW
h3QbWNtQLxTuIvLq5aAryV3PfaHlPgdgK0ft6ocU2de2FagFka3nfVEyC7IUsNTK
bq6nhAECgYEA7d/0DPOIaItl/8BWKyCuAHMss47j0wlGbBSHdJIiS55akMvnAG0M
39y22Qqfzh1at9kBFeYeFIIU82ZLF3xOcE3z6pJZ4Dyvx4BYdXH77odo9uVK9s1l
3T3BlMcqd1hvZLMS7dviyH79jZo4CXSHiKzc7pQ2YfK5eKxKqONeXuECgYEAyXlG
vonaus/YTb1IBei9HwaccnQ/1HRn6MvfDjb7JJDIBhNClGPt6xRlzBbSZ73c2QEC
6Fu9h36K/HZ2qcLd2bXiNyhIV7b6tVKk+0Psoj0dL9EbhsD1OsmE1nTPyAc9XZbb
OPYxy+dpBCUA8/1U9+uiFoCa7mIbWcSQ+39gHuECgYAz82pQfct30aH4JiBrkNqP
nJfRq05UY70uk5k1u0ikLTRoVS/hJu/d4E1Kv4hBMqYCavFSwAwnvHUo51lVCr/y
xQOVYlsgnwBg2MX4+GjmIkqpSVCC8D7j/73MaWb746OIYZervQ8dbKahi2HbpsiG
8AHcVSA/agxZr38qvWV54QKBgCD5TlDE8x18AuTGQ9FjxAAd7uD0kbXNz2vUYg9L
hFL5tyL3aAAtUrUUw4xhd9IuysRhW/53dU+FsG2dXdJu6CxHjlyEpUJl2iZu/j15
YnMzGWHIEX8+eWRDsw/+Ujtko/B7TinGcWPz3cYl4EAOiCeDUyXnqnO1btCEUU44
DJ1BAoGBAJuPD27ErTSVtId90+M4zFPNibFP50KprVdc8CR37BE7r8vuGgNYXmnI
RLnGP9p3pVgFCktORuYS2J/6t84I3+A17nEoB4xvhTLeAinAW/uTQOUmNicOP4Ek
2MsLL2kHgL8bLTmvXV4FX+PXphrDKg1XxzOYn0otuoqdAQrkK4og
-----END RSA PRIVATE KEY-----
EOD;
        $publicKey = <<<EOD
-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAuzWHNM5f+amCjQztc5QT
fJfzCC5J4nuW+L/aOxZ4f8J3FrewM2c/dufrnmedsApb0By7WhaHlcqCh/ScAPyJ
hzkPYLae7bTVro3hok0zDITR8F6SJGL42JAEUk+ILkPI+DONM0+3vzk6Kvfe548t
u4czCuqU8BGVOlnp6IqBHhAswNMM78pos/2z0CjPM4tbeXqSTTbNkXRboxjU29vS
opcT51koWOgiTf3C7nJUoMWZHZI5HqnIhPAG9yv8HAgNk6CMk2CadVHDo4IxjxTz
TTqo1SCSH2pooJl9O8at6kkRYsrZWwsKlOFE2LUce7ObnXsYihStBUDoeBQlGG/B
wQIDAQAB
-----END PUBLIC KEY-----
EOD;
        $payload              = [
            "issuer"     => "shershon",
            "issuer_at"  => time(),
            "expires_at" => time() + 60
        ];
        $encryt               = EncryptTools::jwtEncode($payload, $privateKey, 'RS256');
        $result['rs256-加密结果'] = $encryt;
        $result['rs256-解密结果'] = (array)EncryptTools::jwtDecode($encryt, $publicKey, 'RS256');
        print_r($result);
    }
}