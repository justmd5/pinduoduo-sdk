<h1 align="center">拼多多API SDK【拼多多开放平台】</h1>

<p align="center">
<a href="https://styleci.io/repos/153218715"><img src="https://styleci.io/repos/153218715/shield?branch=master" alt="styleci"></a>
<a href="https://packagist.org/packages/justmd5/pinduoduo-sdk"><img src="https://img.shields.io/packagist/php-v/justmd5/pinduoduo-sdk.svg" alt="PHP from Packagist"></a>
<a href="https://packagist.org/packages/justmd5/pinduoduo-sdk"><img src="https://poser.pugx.org/justmd5/pinduoduo-sdk/downloads.svg" alt="Total Downloadsn"></a>
    <a href="https://packagist.org/packages/justmd5/pinduoduo-sdk"><img src="https://img.shields.io/github/stars/justmd5/pinduoduo-sdk.svg?style=social&label=Stars" alt="GitHub stars"></a>
<a href="https://packagist.org/packages/justmd5/pinduoduo-sdk"><img src="https://poser.pugx.org/justmd5/pinduoduo-sdk/v/stable.svg" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/justmd5/pinduoduo-sdk"><img src="https://poser.pugx.org/justmd5/pinduoduo-sdk/v/unstable.svg" alt="Latest Unstable Version"></a>
<a href="https://app.fossa.io/projects/git%2Bgithub.com%2Fjustmd5%2Fpinduoduo-sdk?ref=badge_shield" alt="FOSSA Status"><img src="https://app.fossa.io/api/projects/git%2Bgithub.com%2Fjustmd5%2Fpinduoduo-sdk.svg?type=shield"/></a>
    <a href="https://packagist.org/packages/justmd5/pinduoduo-sdk"><img src="https://img.shields.io/github/license/justmd5/pinduoduo-sdk.svg" alt="License"></a>
</p>

### 要求
1. PHP >= 7.0
2. **[Composer](https://getcomposer.org/)**
3. ext-curl 拓展
4. ext-json 拓展

### 安装

`composer require justmd5/pinduoduo-sdk`

### 使用

```php

use \Justmd5\PinDuoDuo\PinDuoDuo;

require __DIR__ . '/vendor/autoload.php';
$config = [
    'client_id'    => 'xxxxxx69e3940c6b93xxxxxx',
    'client_secret' => 'c2eda0c398xxxxxxbd63ff57bf22c05xxxxxx',
    'debug'              => true,
    'member_type'        => 'JINBAO',//用户角色 ：MERCHANT(商家授权),H5(移动端),多多客(JINBAO),
    'redirect_uri'       => 'https://test.xxx.com/callback',
    'log'                => [
        'name'       => 'pinduoduo',
        'file'       => __DIR__ . '/pinduoduo.log',
        'level'      => 'debug',
        'permission' => 0777,
    ],
];
$pinduoduo = new PinDuoDuo($config);

```
### 调用示例

>因目前我只有多多客角色账号，所以示例以多多客接口为例，其他两种角色理论相同，请自行尝试
#### 调用无需授权接口示例
> 多多进宝商品详情查询 pdd.ddk.goods.detail
```php
$result   = $pinduoduo->api->request('pdd.ddk.goods.detail', ['goods_id_list' => ['395581006']]);

```
#### 调用需授权接口示例

* 获取授权 URL
```php
$url = $pinduoduo->pre_auth->authorizationUrl();
```
* 重定向到授权页面
```php
$pinduoduo->pre_auth->authorizationRedirect();
```
* 在重定向页面，你可以获取此次授权账号的 token
```php
$token = $pinduoduo->pre_auth->getAccessToken();
//也可以通过上面得到的 refresh_token 去刷新令牌
//$token = $pinduoduo->pre_auth->refreshToken($token['refresh_token']);
```
* 创建授权应用
```php
$pinduoduo = $pinduoduo->oauth->createAuthorization($token['token']);
```
> 获取当前账号下有多少推广位 pdd.ddk.oauth.goods.pid.query
```php
$result   = $pinduoduo->auth_api->request('pdd.ddk.oauth.goods.pid.query');
```
### 文档
[拼多多开放平台](http://open.pinduoduo.com/)  · [官方文档](http://open.pinduoduo.com/#/apidocument)


### 感谢

-  [hanson/foundation-sdk](https://github.com/Hanson/foundation-sdk)

## License

MIT


[![FOSSA Status](https://app.fossa.io/api/projects/git%2Bgithub.com%2Fjustmd5%2Fpinduoduo-sdk.svg?type=large)](https://app.fossa.io/projects/git%2Bgithub.com%2Fjustmd5%2Fpinduoduo-sdk?ref=badge_large)
