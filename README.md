## 描述
在做PHP项目过程做，会发现经常性的重复造轮子，为了以后少造轮子就
多收集日常开发过程中经常用到的轮子，避免二次开发。做个效率高的懒汉。

**[查看源码](https://github.com/shershon-php-package/php-tools.git)**

## 版本要求
```shell script
php >= 7.1
```

## 安装
```shell script
composer require shershon/php-tools
```
## 文件介绍
| 文件               | 说明         |
| ------------------ | ------------ |
| src/ArrayTools.php      | 数组相关方法 |
| src/ComputeTools.php    | 计算相关方法 |
| src/DateTools.php       | 日期相关方法 |
| src/DecideTools.php     | 判断相关方法 |
| src/FileTools.php       | 文件相关方法 |
| src/HttpTools.php       | http相关方法 |
| src/NumberTools.php     | 数字相关方法 |
| src/StringTools.php     | 字符串相关方法 |
| src/ValidateTools.php   | 验证相关方法 |
| src/client/RedisClient.php  |  连接redis客户端 |
| src/EncryptTools.php  |  加解密类 |
| src/ImageTools.php      |  图片处理类 |


## 代码测试
```sh
cd tests
phpunit ArrayTest.php
```