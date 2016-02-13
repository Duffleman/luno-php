<?php

namespace Duffleman\Luno;

use GuzzleHttp\Client;

class LunoRequester
{

    private $guzzle;

    private $config = [
        'host'    => 'https://api.luno.io',
        'version' => 1,
        'key'     => null,
        'secret'  => null,
        'timeout' => 10000,
    ];

    private $endpoint;

    public function __construct(array $config = [], Client $guzzle = null)
    {
        $this->guzzle = $guzzle ?: new Client([
            'defaults' => [
                'headers' => [
                    'accept'     => 'application/json',
                    'user-agent' => 'luno_php/v1'
                ]
            ]
        ]);
        $this->endpoint = '/v' . $this->config['version'];
        $this->overrideConfig($config);
    }

    private function overrideConfig(array $config)
    {
        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }
    }

    public function request($method, $route, $params = [], $body = null)
    {
        $params['key'] = $this->config['key'];
        $params['timestamp'] = $this->buildTimestamp();

        $route = $this->endpoint . $route;

        $query_string = http_build_query($params);
        $sign = "{$method}:{$route}?{$query_string}";

        if ($body) {
            $sign += ":" . json_encode($body);
        }

        $verified_sign = hash_hmac('sha512', utf8_encode($sign), utf8_encode($this->config['secret']));

        $params['sign'] = $verified_sign;

        $response = $this->guzzle->request($method, $this->config['host'] . $route, [
            'body'  => $body ?: null,
            'query' => $params,
        ]);

        return (string)$response->getBody();
    }

    private function buildTimestamp()
    {
        return date('c');
    }
}