<?php
/**
 * Created for pinduoduo-sdk.
 * User: 丁海军
 * Date: 2018/10/13
 * Time: 15:05.
 */

namespace Justmd5\PinDuoDuo\Oauth;

use Justmd5\PinDuoDuo\PinDuoDuo;

class PreAuth
{
    const AUTHORIZE_API_ARR = [
        'MERCHANT' => 'https://mms.pinduoduo.com/open.html?',
        'H5'       => 'https://mai.pinduoduo.com/h5-login.html?',
        'JINBAO'   => 'https://jinbao.pinduoduo.com/open.html?',
    ];
    /**
     * @var PinDuoDuo
     */
    private $app;

    public function __construct(PinDuoDuo $app)
    {
        $this->app = $app;
    }

    /**
     * 重定向至授权 URL.
     *
     * @param      $state
     * @param null $view
     */
    public function authorizationRedirect($state = 'state', $view = null)
    {
        $url = $this->authorizationUrl($state, $view);

        header('Location:'.$url);
    }

    private function accessToken()
    {
        return $this->app['oauth.access_token'];
    }

    /**
     * 获取授权URL.
     *
     * @param string $state
     * @param string $view
     *
     * @return string
     */
    public function authorizationUrl($state = null, $view = null)
    {
        return self::AUTHORIZE_API_ARR[strtoupper($this->app->getConfig('member_type'))].http_build_query([
            'client_id'     => $this->accessToken()->getClientId(),
            'response_type' => 'code',
            'state'         => $state,
            'redirect_uri'  => $this->accessToken()->getRedirectUri(),
            'view'          => $view,
        ]);
    }

    /**
     * 获取 access token.
     *
     * @param      $code
     * @param null $state
     *
     * @return mixed
     */
    public function getAccessToken($code = null, $state = null)
    {
        return $this->accessToken()->token([
            'client_id'     => $this->accessToken()->getClientId(),
            'client_secret' => $this->accessToken()->getSecret(),
            'grant_type'    => 'authorization_code',
            'code'          => $code ?? $this->accessToken()->getRequest()->get('code'),
            'redirect_uri'  => $this->accessToken()->getRedirectUri(),
            'state'         => $state,
        ]);
    }

    /**
     * 刷新令牌.
     *
     * @param      $refreshToken
     * @param null $state
     *
     * @return mixed
     */
    public function refreshToken($refreshToken, $state = null)
    {
        return $this->accessToken()->token([
            'client_id'     => $this->accessToken()->getClientId(),
            'client_secret' => $this->accessToken()->getSecret(),
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
            'state'         => $state,
        ]);
    }
}
