## 1.常用的工具包

在做PHP项目过程做，会发现经常性的重复造轮子，为了以后少造轮子就 多收集日常开发过程中经常用到的轮子，避免二次开发。做个效率高的懒汉。

工具包如下：

| 文件                    | 说明          |
|-----------------------|-------------|
| src/ArrayTools.php    | 数组相关方法      |
| src/ComputeTools.php  | 计算相关方法      |
| src/DateTools.php     | 日期相关方法      |
| src/DecideTools.php   | 判断相关方法      |
| src/FileTools.php     | 文件相关方法      |
| src/HttpTools.php     | http相关方法    |
| src/NumberTools.php   | 数字相关方法      |
| src/StringTools.php   | 字符串相关方法     |
| src/ValidateTools.php | 验证相关方法      |
| src/RedisTools.php    | Redis操作类    |
| src/EncryptTools.php  | 加解密类        |
| src/ImageTools.php    | 图片处理类       |
| src/ElasticTools.php  | ES操作类       |
| src/CaptchaTools.php  | 验证码操作类      |
| src/RocketmqTools.php  | Rocketmq操作类 |

**[查看源码](https://github.com/shershon-php-package/php-tools.git)**

## 2.版本要求

```shell script
php >= 7.1
```

## 3.安装

- 配置composer.json
```json
{
  "require-dev": {
    "shershon/php-tools": "^1.0.0"
  },
  "config": {
    "secure-http": false
  },
  "repositories": [
    {
      "type": "git",
      "url": "https://github.com/shershon-php-package/php-tools.git"
    }
  ]
}
```

- composer require --ignore-platform-reqs shershon/php-tools
- rm -rf vendor/shershon/php-tools/.git

## 4.代码测试

### 4.1 测试全部用例

```bash
phpunit -c phpunit.xml --colors=always tests/Cases/RedisTest.php
```

### 4.2 测试单个用例

```bash
phpunit -c phpunit.xml --colors=always tests/Cases/RedisTest.php --filter testGetVal
```