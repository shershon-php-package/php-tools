<?php

namespace PhpTools;

/**
 * @Description: 文件操作类
 * @Class FileTools
 * @Package PhpTools
 */
class FileTools
{

    /**
     * @description: 递归删除文件夹和文件
     * @param $dirname
     * @param bool $delete_self
     * @autor Shershon
     */
    public static function recursiveDelete($dirname, $delete_self = true)
    {
        $dirname = rtrim($dirname, '/') . '/';
        if (is_dir($dirname)) {
            $files = scandir($dirname);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    if (is_dir($dirname . $file)) {
                        self::recursiveDelete($dirname . $file, true);
                    } elseif (file_exists($dirname . $file)) {
                        unlink($dirname . $file);
                    }
                }
            }
            if ($delete_self) {
                rmdir($dirname);
            }
        }
    }


    /**
     * @description: 遍历一个文件夹下的所有文件和子文件夹
     * @param $dir
     * @return array|bool
     * @autor Shershon
     */
    public static function recursiveDeleteForeach($dir)
    {
        $files = [];
        if (!is_dir($dir)) {
            return $dir;
        }
        $handle = opendir($dir);
        if (!$handle) {
            return false;
        }
        //取出.和..
        readdir($handle);
        readdir($handle);

        //遍历剩余的文件和目录
        while ($file = readdir($handle)) {
            if (is_dir($file)) {
                $files[$file] = self::recursiveDeleteForeach($file);
            } else {
                $files[] = $dir . '/' . $file;
            }
        }
        closedir($handle);
        return $files;
    }


    /**
     * @description: 获取文件扩展名
     * @param $file
     * @return string|string[]
     * @autor Shershon
     */
    public static function getFileExtension($file)
    {
        if (is_uploaded_file($file)) {
            return "unknown";
        }
        return pathinfo($file, PATHINFO_EXTENSION);
    }


    /**
     * @description: 创建多级目录
     * @param $dir
     * @return bool
     * @autor Shershon
     */
    public static function mkdirs($dir)
    {
        return is_dir($dir) or (self::mkdirs(dirname($dir)) and mkdir(
                    $dir,
                    0777
                ));
    }


    /**
     * @description: base64转pdf
     * @param $fileName
     * @param $baseStr
     * @param $logPath
     * @return false|int|string
     * @autor Shershon
     */
    public static function Base642Pdf(
        $fileName,
        $baseStr,
        $logPath
    )
    {
        //首先判断目录是否存在,不存在则创建;
        if (!is_dir($logPath)) {
            self::mkdirs($logPath);
        }
        $fileName .= '.pdf';
        $logfile = rtrim($logPath, '/') . '/' . $fileName;
        $strContent = base64_decode($baseStr);
        $saveResult = file_put_contents($logfile, $strContent, FILE_APPEND);
        if ($saveResult) {
            return $fileName;
        }
        return $saveResult;
    }


    /**
     * @description: 获取服务器配置允许最大上传文件大小
     * @param int $max_size
     * @return mixed //返回单位是 byte
     * @autor Shershon
     */
    public static function getMaxUploadSize($max_size = 0)
    {
        $post_max_size = ComputeTools::convertBytes(ini_get('post_max_size'));
        $upload_max_filesize = ComputeTools::convertBytes(
            ini_get('upload_max_filesize')
        );
        if ($max_size > 0) {
            $result = min($post_max_size, $upload_max_filesize, $max_size);
        } else {
            $result = min($post_max_size, $upload_max_filesize);
        }

        return $result;
    }
}