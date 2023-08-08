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
        'MERCHANT'  => 'https://fuwu.pinduoduo.com/service-market/auth',   //拼多多店铺,WEB端网页授权
        'H5'        => 'https://mai.pinduoduo.com/h5-login.html',          //拼多多店铺,H5移动端网页授权
        'JINBAO'    => 'https://jinbao.pinduoduo.com/open.html',           //多多进宝推手,WEB端网页授权
        'KTT'       => 'https://oauth.pinduoduo.com/authorize/ktt',        //快团团团长,WEB端网页授权
        'LOGISTICS' => 'https://wb.pinduoduo.com/logistics/auth',          //拼多多电子面单用户,WEB端网页授权
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
     * @param string      $state
     * @param string|null $view
     */
    public function authorizationRedirect(string $state = 'state', string $view = null)
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
     * @param string|null $state
     * @param string|null $view
     *
     * @return string
     */
    public function authorizationUrl(string $state = null, string $view = null): string
    {
        return sprintf("%s?%s", static::AUTHORIZE_API_ARR[strtoupper($this->app->getConfig('member_type'))],
            http_build_query([
                'client_id'     => $this->accessToken()->getClientId(),
                'response_type' => 'code',
                'state'         => $state,
                'redirect_uri'  => $this->accessToken()->getRedirectUri(),
                'view'          => $view,
            ]));
    }

    /**
     * 获取 access token.
     *
     * @param string|null $code
     * @param null        $state
     *
     * @return mixed
     */
    public function getAccessToken(string $code = null, $state = null)
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
     * @param string      $refreshToken
     * @param string|null $state
     *
     * @return mixed
     */
    public function refreshToken(string $refreshToken, string $state = null)
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
