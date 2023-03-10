<?php

namespace PhpTools;

/**
 * @Description: 验证码操作类
 * @Class CaptchaTools
 * @Package PhpTools
 */
class CaptchaTools
{
    private $_image;
    private $_bgcolor;
    private $_captch_code;

    public function __construct($width, $height)
    {
        //session_start();
        $this->_image   = imagecreatetruecolor($width, $height);
        $this->_bgcolor = imagecolorallocate($this->_image, 255, 255, 255);
        imagefill($this->_image, 0, 0, $this->_bgcolor);
    }

    /**
     * 创建数字验证码
     *
     * @return void
     */
    public function createNumber()
    {
        $this->_captch_code = '';
        for ($i = 0; $i < 4; $i++) {
            $fontsize  = 6;
            $fontcolor = imagecolorallocate($this->_image, rand(0, 120), rand(0, 120), rand(0, 120));

            $fontcontent        = rand(0, 9);
            $this->_captch_code .= $fontcontent;

            $x = ($i * 100 / 4) + rand(5, 10);
            $y = rand(5, 10);
            imagestring($this->_image, $fontsize, $x, $y, $fontcontent, $fontcolor);
        }
        $this->disturbDot();
        $this->disturbLine();
        $_SESSION['authcode'] = $this->_captch_code; // 保存到session，以便提交表单时进行验证
        $this->saveToImg();
    }

    /**
     * 创建数字、字母验证码
     *
     * @return void
     */
    public function createNumberAndLetter()
    {
        $this->_captch_code = '';
        for ($i = 0; $i < 4; $i++) {
            $fontsize  = 6;
            $fontcolor = imagecolorallocate($this->_image, rand(0, 120), rand(0, 120), rand(0, 120));

            $data               = 'abcdefghijkmnpqrstuvwxy3456789';
            $fontcontent        = substr($data, rand(0, strlen($data)), 1);
            $this->_captch_code .= $fontcontent;

            $x = ($i * 100 / 4) + rand(5, 10);
            $y = rand(5, 10);
            imagestring($this->_image, $fontsize, $x, $y, $fontcontent, $fontcolor);
        }
        $this->disturbDot();
        $this->disturbLine();
        $_SESSION['authcode'] = $this->_captch_code; // 保存到session，以便提交表单时进行验证
        $this->saveToImg();
    }

    /**
     * 创建汉字验证码
     *
     * @return void
     */
    public function createChinese()
    {
        //创建汉字验证码
        $fontface = __DIR__ . '/../public/font/msyh.ttf';
        $str      = "你要加油你是最棒的努力成为自己心目中的那个人";
        $strdb    = str_split($str, 3);

        $this->_captch_code = '';
        for ($i = 0; $i < 4; $i++) {
            $fontcolor          = imagecolorallocate($this->_image, rand(0, 120), rand(0, 120), rand(0, 120));
            $index              = rand(0, count($strdb) - 1);
            $cn                 = $strdb[$index];
            $this->_captch_code .= $cn;

            imagettftext($this->_image, mt_rand(20, 24), mt_rand(-60, 60), (40 * $i + 20), mt_rand(30, 35), $fontcolor, $fontface, $cn);
        }
        $this->disturbDot();
        $this->disturbLine();
        $_SESSION['authcode'] = $this->_captch_code; // 保存到session，以便提交表单时进行验证
        $this->saveToImg();
    }

    //增加干扰点
    protected function disturbDot()
    {
        for ($i = 0; $i < 200; $i++) {
            $pointcolor = imagecolorallocate($this->_image, rand(50, 200), rand(50, 200), rand(50, 200));
            imagesetpixel($this->_image, rand(1, 99), rand(1, 29), $pointcolor);
        }
    }

    //增加干扰线
    protected function disturbLine()
    {
        for ($i = 0; $i < 3; $i++) {
            $linecolor = imagecolorallocate($this->_image, rand(80, 220), rand(80, 220), rand(80, 220));
            imageline($this->_image, rand(1, 99), rand(1, 29), rand(1, 99), rand(1, 29), $linecolor);
        }
    }

    // 输出到浏览器
    protected function pushToBrowser()
    {
        header('content-type:image/png');
        imagepng($this->_image);
    }

    // 保存至图片
    protected function saveToImg()
    {
        imagepng($this->_image, __DIR__ . '/../public/img/captcha.png');
        imagedestroy($this->_image);
    }
}