<?php

namespace ToolsTest\Cases;

use PhpTools\CaptchaTools;
use PHPUnit\Framework\TestCase;

class CaptchaTest extends TestCase
{
    // 创建数字验证码
    public function testCreateNumber()
    {
        (new CaptchaTools(100, 30))->createNumber();
    }

    // 创建数字和字母验证码
    public function testCreateNumberLetter()
    {
        (new CaptchaTools(100, 30))->createNumberAndLetter();
    }

    // 创建汉字验证码
    public function testCreateChinese()
    {
        (new CaptchaTools(200, 60))->createChinese();
    }
}
