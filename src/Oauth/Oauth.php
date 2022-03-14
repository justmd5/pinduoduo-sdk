<?php
/**
 * Created for pinduoduo-sdk.
 * User: 丁海军
 * Date: 2018/10/13
 * Time: 15:05.
 */

namespace Justmd5\PinDuoDuo\Oauth;

use Justmd5\PinDuoDuo\PinDuoDuo;

class Oauth
{
    /**
     * @var PinDuoDuo
     */
    private $app;

    public function __construct(PinDuoDuo $app)
    {
        $this->app = $app;
    }

    /**
     * @param string $token
     * @param int    $expires
     *
     * @return PinDuoDuo
     */
    public function createAuthorization(string $token, int $expires = 86399): PinDuoDuo
    {
        $accessToken = new AccessToken(
            $this->app->getConfig('client_id'),
            $this->app->getConfig('client_secret'),
            $this->app
        );

        $accessToken->setToken($token, $expires);

        $this->app->access_token = $accessToken;

        return $this->app;
    }
}
