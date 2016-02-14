<?php

namespace Duffleman\Luno;

use Duffleman\Luno\Users\User;
use GuzzleHttp\Client;

class LunoRequester
{

    private $guzzle;
    private $user;

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

    public function request($method, $route, $params = [], array $body = [])
    {
        $params['key'] = $this->config['key'];
        $params['timestamp'] = $this->buildTimestamp();

        $route = $this->endpoint . $route;

        ksort($params);
        $query_string = http_build_query($params);
        $sign = "{$method}:{$route}?{$query_string}";

        if ($body) {
            $sign .= ":" . json_encode($body);
        }

        $verified_sign = hash_hmac('sha512', utf8_encode($sign), utf8_encode($this->config['secret']));

        $params['sign'] = $verified_sign;

        switch($method) {
            case 'DELETE':
            case 'GET':
                $response = $this->guzzle->request($method, $this->config['host'] . $route, [
                    'body' => json_encode($body) ?: null,
                    'query' => $params,
                ]);
                break;
            case 'PUT':
            case 'PATCH':
            case 'POST':
                $response = $this->guzzle->request($method, $this->config['host'] . $route, [
                    'body' => json_encode($body) ?: null,
                    'headers' => [
                        'content-type' => 'application/json',
                    ],
                    'query' => $params,
                ]);
                break;
        }

        $jsonResponse = (string)$response->getBody();

        return json_decode($jsonResponse, true);
    }

    private function buildTimestamp()
    {
        return date('Y-m-d\TH:i:s.000\Z');
    }

    public function __get($variable_name) {
        if($variable_name === 'user') {
            if(!is_null($this->user)) {
                return $this->user;
            } else {
                return new User($this);
            }
        }
    }
}