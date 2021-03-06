<?php

namespace Duffleman\Luno;

use Duffleman\Luno\Collections\ApiCollection;
use Duffleman\Luno\Collections\EventCollection;
use Duffleman\Luno\Collections\SessionCollection;
use Duffleman\Luno\Collections\UserCollection;
use Duffleman\Luno\Contracts\ResultManager;
use Duffleman\Luno\Exceptions\LunoApiException;
use Duffleman\Luno\Exceptions\LunoLibraryException;
use Duffleman\Luno\Interactors\AccountInteractor;
use Duffleman\Luno\Interactors\AnalyticsInteractor;
use Duffleman\Luno\Managers\ArrayManager;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;

/**
 * Class LunoRequester
 *
 * @package Duffleman\Luno
 */
final class LunoRequester
{

    /**
     * Holds array to map __get variables to a return class.
     *
     * @var array
     */
    private static $classmap = [
        'account'   => AccountInteractor::class,
        'apikeys'   => ApiCollection::class,
        'analytics' => AnalyticsInteractor::class,
        'events'    => EventCollection::class,
        'sessions'  => SessionCollection::class,
        'users'     => UserCollection::class,
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
     * Holds the result manager.
     *
     * @var ResultManager
     */
    private $manager;

    /**
     * LunoRequester constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = [])
    {
        $this->manager = isset($config['manager']) ? $config['manager'] : new ArrayManager();
        $this->guzzle = isset($config['client']) ? $config['client'] : new Client([
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
     * Send a request to Luno.io.
     *
     * @param       $method
     * @param       $route
     * @param array $params
     * @param array $body
     * @param bool  $returnRawResult
     * @return mixed
     * @throws LunoApiException
     * @throws LunoLibraryException
     */
    public function request($method, $route, array $params = [], array $body = [], $returnRawResult = false)
    {
        $this->preventBadRequest();

        // Build up required params.
        $params['key'] = $this->config['key'];
        $params['timestamp'] = $this->buildTimestamp();
        if ($this->isSandbox()) {
            $params['sandbox'] = true;
        }

        // Set our route.
        $route = $this->endpoint . $route;

        // Sort params alphabetically by key.
        ksort($params);
        ksort($body);
        // Build query string.
        $query_string = http_build_query($params, null, '&', PHP_QUERY_RFC3986);

        // Build sign string.
        $sign = "{$method}:{$route}?{$query_string}";

        // If we have a body, append to the sign string.
        if (!empty($body)) {
            $body = $this->buildBody($body);
            $sign .= ":" . $body;
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
            $send_body = $body;
        }

        // Try and send the request.
        try {
            $response = $this->guzzle->request($method, $this->config['host'] . $route, [
                'body'    => $send_body,
                'headers' => $headers,
                'query'   => $params,
            ]);
            $jsonResponse = (string)$response->getBody();
            $resultSet = json_decode($jsonResponse, true);

            if ($returnRawResult) {
                return $resultSet;
            } else {
                return $this->manager->translate($resultSet);
            }
        } catch (ClientException $exception) {
            $rawResponse = json_decode((string)$exception->getResponse()->getBody(), true);
            throw new LunoApiException($rawResponse);
        }
    }

    /**
     * Prevents bad requests being sent.
     *
     * @throws LunoLibraryException
     */
    private function preventBadRequest()
    {
        if (empty($this->config['key']) || empty($this->config['secret'])) {
            throw new LunoLibraryException('You need to set both a key and secret before sending a request.');
        }
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
     * Is the Requester set to use the Sandbox environment?
     *
     * @return bool
     */
    public function isSandbox()
    {
        return isset($this->config['sandbox']) && $this->config['sandbox'] === true;
    }

    /**
     * Build the body string from an array.
     *
     * @param array $body
     * @return string
     */
    private function buildBody(array $body)
    {
        $body = $this->fixEmptyArrays($body);

        return utf8_encode(json_encode($body, JSON_UNESCAPED_SLASHES));
    }

    /**
     * Turns the empty arrays into objects so when JSON encoded it shows properly.
     *
     * @param array $body
     * @return array
     */
    private function fixEmptyArrays(array $body)
    {
        foreach ($body as $key => $value) {
            if (is_null($value)) {
                $body[$key] = null;
            } elseif (empty($value)) {
                $body[$key] = (object)$value;
            }
        }

        return $body;
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
        if (!array_key_exists($name, static::$classmap)) {
            throw new LunoLibraryException("Unable to find appropriate collection.");
        }

        $class = static::$classmap[$name];

        return new $class($this);
    }

    /**
     * Gets the manager.
     *
     * @return ResultManager
     */
    public function getManager()
    {
        return $this->manager;
    }
}