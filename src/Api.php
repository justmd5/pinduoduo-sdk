<?php
/**
 * Created for pinduoduo-sdk.
 * User: 丁海军
 * Date: 2018/10/13
 * Time: 15:05.
 */

namespace Justmd5\PinDuoDuo;

use Hanson\Foundation\AbstractAPI;

class Api extends AbstractAPI
{
    const URL = 'http://gw-api.pinduoduo.com/api/router';

    /**
     * @var pinduoduo
     */
    protected $pinduoduo;
    protected $needToken;

    public function __construct(PinDuoDuo $pinduoduo, $needToken = false)
    {
        $this->pinduoduo = $pinduoduo;
        $this->needToken = $needToken;
    }

    /**
     * @param $params
     *
     * @return string
     */
    private function signature($params)
    {
        ksort($params);
        $paramsStr = '';
        array_walk($params, function ($item, $key) use (&$paramsStr) {
            if ('@' != substr($item, 0, 1)) {
                $paramsStr .= sprintf('%s%s', $key, $item);
            }
        });

        return strtoupper(md5(sprintf('%s%s%s', $this->pinduoduo['oauth.access_token']->getSecret(), $paramsStr, $this->pinduoduo['oauth.access_token']->getSecret())));
    }

    /**
     * @param string $method
     * @param array  $params
     * @param string $data_type
     *
     * @return mixed
     */
    public function request($method, $params=[], $data_type = 'JSON')
    {
        $http = $this->getHttp();
        $params = $this->paramsHandle($params);
        if ($this->needToken) {
            $params['access_token'] = $this->pinduoduo['oauth.access_token']->getToken();
        }
        $params['client_id'] = $this->pinduoduo['oauth.access_token']->getClientId();
        $params['sign_method'] = 'md5';
        $params['type'] = $method;
        $params['data_type'] = $data_type;
        $params['timestamp'] = strval(time());
        $params['sign'] = $this->signature($params);
        $response = call_user_func_array([$http, 'post'], [self::URL, $params]);
        $responseBody = strval($response->getBody());

        return strtolower($data_type) == 'json' ? json_decode($responseBody, true) : $responseBody;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function paramsHandle(array $params)
    {
        array_walk($params, function (&$item) {
            if (is_array($item)) {
                $item = json_encode($item);
            }
            if (is_bool($item)) {
                $item = ['false', 'true'][intval($item)];
            }
        });

        return $params;
    }
}
