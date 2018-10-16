<?php
/**
 * Created for pinduoduo-sdk.
 * User: 丁海军
 * Date: 2018/10/13
 * Time: 15:05.
 */

namespace Justmd5\PinDuoDuo\Oauth;

use Justmd5\PinDuoDuo\AccessToken as BaseAccessToken;
use Symfony\Component\HttpFoundation\Request;

class AccessToken extends BaseAccessToken
{
    /**
     * @var Request
     */
    protected $request;

    protected $redirectUri;

    /**
     * 获取 token from server.
     *
     * @param $params
     *
     * @return mixed
     */
    public function token($params)
    {
        $response = $this->getHttp()->json(self::TOKEN_API, $params);

        return json_decode(strval($response->getBody()), true);
    }

    public function setRequest(Request $request)
    {
        $this->request = $request;
    }

    public function setRedirectUri($redirectUri)
    {
        $this->redirectUri = $redirectUri;
    }

    /**
     * @return mixed
     */
    public function getRedirectUri()
    {
        return $this->redirectUri;
    }

    /**
     * @return Request
     */
    public function getRequest()
    {
        return $this->request;
    }
}
