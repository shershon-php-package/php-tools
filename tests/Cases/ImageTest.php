<?php

namespace ToolsTest\Cases;

use PHPUnit\Framework\TestCase;
use PhpTools\ImageTools;

class ImageTest extends TestCase
{
    public function testFontMark()
    {
        $src      = __DIR__ . '/../public/img/001.jpg';
        $font_url = __DIR__ . '/../public/font/msyh.ttf';
        $source   = __DIR__ . '/../public/img/002.gif';
        $local    = array(
            'x' => 50,
            'y' => 400,
        );
        $content  = 'hello,world';
        $size     = 20;
        $color    = array(
            0 => 255,
            1 => 0,
            2 => 0,
        );
        $local2   = array(
            'x' => 250,
            'y' => 60,
        );
        $angle    = 10;
        $alpha    = 50;
        $image    = new ImageTools($src);
        $image->imageMark($source, $local, $alpha);
        $image->thumb(400, 300);
        $image->fontMark($content, $font_url, $size, $color, $local2, $angle);
        //$image->show();
        $image->save(__DIR__ . '/../public/img/fontMark');
    }

    public function testNewImage()
    {
        /* 打开图片 */
        //1.配置图片路径（想要操作的图片的路径）
        $src = __DIR__ . '/../public/img/001.jpg';
        //2.获取图片信息（通过GD库提供的方法得到你想要处理图片的基本信息）
        $info = getimagesize($src);
        //3.通过图像的编号获得图像的类型
        $type = image_type_to_extension($info[2], false);
        //4.在内存中创建一个和图像类型一样的图像
        $fun = "imagecreatefrom{$type}";
        //5.把图像复制到内存中
        $image = $fun($src);
        /* 操作图片 */
        //1.设置字体的路径
        $font_url = __DIR__ . '/../public/font/msyh.ttf';
        //2.填写水印的内容
        $content = '你好，慕课';
        //3.设置字体的颜色RGB和透明度 参数1 内存中的图片 2 red 3 green 4 blue
        // $color = imagecolorallocate($image, 255, 255, 255, 50);
        $color = imagecolorallocate($image, 255, 255, 255);
        //4.写入文字
        imagettftext($image, 20, 0, 20, 30, $color, $font_url, $content);
        /* 输出图片 */
        //浏览器输出
        //header("Content-Type:", $info['mime']);
        $func = "image{$type}";
        //$func($image);
        //保存图片
        $func($image, __DIR__ . '/../public/img/newimage.' . $type);
        /* 销毁图片 */
        imagedestroy($image);
    }

    public function testImageMark()
    {
        /* 打开图片 */
        //1.配置图片路径
        $src = __DIR__ . '/../public/img/001.jpg';
        //2.获取图片的基本信息
        $info = getimagesize($src);
        //3.通过图像的编号获取图像的类型
        $type = image_type_to_extension($info[2], false);
        //4.在内存中创建一个和我们图像一样的图像
        $fun = "imagecreatefrom{$type}";
        //5.把要操作的图片复制到内存中
        $image = $fun($src);
        /* 操作图片 */
        //1.设置水印的路径
        $imageMark = __DIR__ . '/../public/img/002.gif';
        //2.获取水印图片的基本信息
        $info2 = getimagesize($imageMark);
        //3.通过水印的图像编号来获取水印的图片类型
        $type2 = image_type_to_extension($info2[2], false);
        //4.在内存中创建一个和我们水印图像一样的图像类型
        $fun2 = "imagecreatefrom{$type2}";
        //5.把水印图片复制到我们的内存中
        $water = $fun2($imageMark);
        //6.给原始图片添加水印图片
        imagecopymerge($image, $water, 20, 30, 0, 0, $info2[0], $info2[1], 50);
        //7.销毁水印图片
        imagedestroy($water);
        /* 输出图片 */
        //在浏览器中输出图片
        //header("Content-Type:", $info['mime']);
        $funs = "image{$type}";
        //$funs($image);
        //保存图片
        $funs($image, __DIR__ . '/../public/img/imageMark.' . $type);
        /* 销毁图片 */
        imagedestroy($image);
    }

    public function testThumbImage()
    {
        /* 打开图片 */
        //1.配置图片路径
        $src = __DIR__ . '/../public/img/001.jpg';
        //2.
        $info = getimagesize($src);
        //3.
        $type = image_type_to_extension($info[2], false);
        //4.
        $fun = "imagecreatefrom{$type}";
        //5.
        $image = $fun($src);
        /* 操作图片 */
        //1.在内存中建立一个宽300，高200的真色彩图片
        $image_thumb = imagecreatetruecolor(300, 200);
        //2.核心步骤，将原图复制到新建的真色彩的图片上，并且按照一定的比例压缩
        imagecopyresampled($image_thumb, $image, 0, 0, 0, 0, 300, 200, $info[0], $info[1]);
        //3.销毁原始图片
        imagedestroy($image);
        /* 输出图片 */
        //输出到浏览器
        //header("Content-Type:", $info['mime']);
        $funs = "image{$type}";
        //$funs($image_thumb);
        //保存到电脑
        $funs($image_thumb, __DIR__ . '/../public/img/thumb_image.' . $type);
        /* 销毁图片 */
        imagedestroy($image_thumb);
    }
}