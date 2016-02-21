<?php

namespace Duffleman\Luno;

use Duffleman\Luno\Collections\{
    ApiCollection, EventCollection, SessionCollection, UserCollection
};
use Duffleman\Luno\Exceptions\LunoApiException;
use Duffleman\Luno\Exceptions\LunoLibraryException;
use Duffleman\Luno\Interactors\AnalyticsInteractor;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class LunoRequester
 *
 * @package Duffleman\Luno
 */
class LunoRequester
{

    /**
     * Holds array to map __get variables to a return class.
     *
     * @var array
     */
    private static $classmap = [
        'api'       => ApiCollection::class,
        'analytics' => AnalyticsInteractor::class,
        'users'     => UserCollection::class,
        'sessions'  => SessionCollection::class,
        'events'    => EventCollection::class,
    ];

    /**
     * Holds the Guzzle client.
     *
     * @var Client
     */
    private $guzzle;

    /**
     * Config array for Luno.
     *
     * @var array
     */
    private $config = [
        'host'    => 'https://api.luno.io',
        'version' => 1,
        'key'     => null,
        'secret'  => null,
        'timeout' => 10000,
    ];

    /**
     * Holds the endpoint for later construction.
     *
     * @var string
     */
    private $endpoint;

    /**
     * LunoRequester constructor.
     *
     * @param array       $config
     * @param Client|null $guzzle
     */
    public function __construct(array $config = [], Client $guzzle = null)
    {
        $this->guzzle = $guzzle ?: new Client([
            'defaults' => [
                'headers' => [
                    'accept'     => 'application/json',
                    'user-agent' => 'luno_php/v1'
                ]
            ],
            'timeout'  => $this->config['timeout'],
        ]);
        $this->endpoint = '/v' . $this->config['version'];
        $this->overrideConfig($config);
    }

    /**
     * Let's us append individual config items rather than overwrite the entire array.
     *
     * @param array $config
     */
    private function overrideConfig(array $config)
    {
        foreach ($config as $key => $value) {
            $this->config[$key] = $value;
        }
    }

    /**
     * The main request method.
     * Send a request to Luno.
     *
     * @param string $method
     * @param string $route
     * @param array  $params
     * @param array  $body
     * @return mixed
     * @throws LunoApiException
     */
    public function request(string $method, string $route, array $params = [], array $body = [])
    {
        // Build up required params.
        $params['key'] = $this->config['key'];
        $params['timestamp'] = $this->buildTimestamp();

        // Set our route.
        $route = $this->endpoint . $route;

        // Sort params alphabetically by key.
        ksort($params);
        // Build query string.
        $query_string = http_build_query($params);
        // Build sign string.
        $sign = "{$method}:{$route}?{$query_string}";

        // If we have a body, append to the sign string.
        if (!empty($body)) {
            $sign .= ":" . json_encode($body);
        }

        // Build the verified sign string.
        $verified_sign = hash_hmac('sha512', utf8_encode($sign), utf8_encode($this->config['secret']));
        // Append the verified sign key to the params.
        $params['sign'] = $verified_sign;

        $headers = [];
        $send_body = false;
        if (!empty($body)) {
            $headers = [
                'content-type' => 'application/json',
            ];
            $send_body = json_encode($body);
        }

        // Try and send the request.
        try {
            $response = $this->guzzle->request($method, $this->config['host'] . $route, [
                'body'    => $send_body,
                'headers' => $headers,
                'query'   => $params,
            ]);
        } catch (ClientException $exception) {
            $rawResponse = json_decode((string)$exception->getResponse()->getBody(), true);
            throw new LunoApiException($rawResponse);
        }

        $jsonResponse = (string)$response->getBody();

        return json_decode($jsonResponse, true);
    }

    /**
     * Build the specific timestamp format that Luno likes.
     *
     * @return string
     */
    private function buildTimestamp()
    {
        return date('c');
    }

    /**
     * I dislike magic getters.
     * But we return an appropriate class where we can ;)
     *
     * @param $name
     * @return mixed
     * @throws LunoLibraryException
     */
    public function __get($name)
    {
        if (array_key_exists($name, static::$classmap)) {
            $class = static::$classmap[$name];

            return new $class($this);
        }

        throw new LunoLibraryException("Unable to find appropriate collection.");
    }

}