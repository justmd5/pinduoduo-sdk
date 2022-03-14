<?php
/**
 * Created for pinduoduo-sdk.
 * User: 丁海军
 * Date: 2018/10/13
 * Time: 15:05.
 */

namespace Justmd5\PinDuoDuo;

use Hanson\Foundation\Foundation;
use Justmd5\PinDuoDuo\Oauth\Oauth;
use Justmd5\PinDuoDuo\Oauth\PreAuth;

/**
 * Class PinDuoDuo.
 *
 * @property Api $api
 * @property Api $auth_api
 * @property AccessToken $access_token
 * @property PreAuth $pre_auth
 * @property Oauth   $oauth
 */
class PinDuoDuo extends Foundation
{
    protected $providers = [
        ServiceProvider::class,
        \Justmd5\PinDuoDuo\Oauth\ServiceProvider::class,
    ];
}
