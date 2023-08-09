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
    const URL = 'https://gw-api.pinduoduo.com/api/router';
    const UPLOAD_URL = 'https://gw-upload.pinduoduo.com/api/upload';

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
    private function signature($params): string
    {
        ksort($params);
        $paramsStr = '';
        array_walk($params, function ($item, $key) use (&$paramsStr) {
            if ('@' != substr($item, 0, 1)) {
                $paramsStr .= sprintf('%s%s', $key, $item);
            }
        });
        $secret = $this->pinduoduo['oauth.access_token']->getSecret();

        return strtoupper(md5(sprintf('%s%s%s', $secret, $paramsStr, $secret)));
    }

    /**
     * @param bool $auth
     *
     * @return $this
     */
    public function auth(bool $auth = true): Api
    {
        $this->needToken = $auth;

        return $this;
    }

    /**
     * @param string $type      API接口名称
     * @param array  $params    请求参数 非公参以外参数
     * @param string $data_type 响应格式，即返回数据的格式，JSON或者XML（二选一），默认JSON，注意是大写
     *
     * @return mixed
     */
    public function request(string $type, array $params = [], string $data_type = 'JSON', $version = 'V1')
    {
        $http = $this->getHttp();
        $params = $this->paramsHandle($params);
        if ($this->needToken) {
            $params['access_token'] = $this->pinduoduo['oauth.access_token']->getToken();
        }
        $params['client_id'] = $this->pinduoduo['oauth.access_token']->getClientId();
        $params['sign_method'] = 'md5';
        $params['type'] = $type;
        $params['data_type'] = $data_type;
        $params['version'] = $version;
        $params['timestamp'] = strval(time());
        $params['sign'] = $this->signature($params);
        $method = 'post';
        $data = [static::URL];
        //文件上传兼容
        if (!empty($params['file'])) {
            $data = [static::UPLOAD_URL];
            $method = 'upload';
            array_push($data, [], ['file' => $params['file']]);
        }
        $data[] = $params;
        $response = call_user_func_array([$http, $method], $data);
        $responseBody = strval($response->getBody());

        return strtolower($data_type) == 'json' ? json_decode($responseBody, true) : $responseBody;
    }

    /**
     * @param array $params
     *
     * @return array
     */
    protected function paramsHandle(array $params): array
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
