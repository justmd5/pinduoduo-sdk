<?php
/**
 * Created for pinduoduo-sdk.
 * User: 丁海军
 * Date: 2018/10/13
 * Time: 15:05.
 */

namespace Justmd5\PinDuoDuo;

use Hanson\Foundation\Foundation;

/**
 * Class PinDuoDuo.
 *
 * @property \Justmd5\PinDuoDuo\Api           $api
 * @property \Justmd5\PinDuoDuo\Api           $auth_api
 * @property \Justmd5\PinDuoDuo\AccessToken   $access_token
 * @property \Justmd5\PinDuoDuo\Oauth\PreAuth $pre_auth
 * @property \Justmd5\PinDuoDuo\Oauth\Oauth   $oauth
 */
class PinDuoDuo extends Foundation
{
    protected $providers = [
        ServiceProvider::class,
        Oauth\ServiceProvider::class,
    ];
}
