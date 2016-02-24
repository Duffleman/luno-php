<?php

namespace Duffleman\Luno\Interactors;

/**
 * Class AnalyticsInteractor
 *
 * @package Duffleman\Luno\Interactors
 */
class AnalyticsInteractor extends BaseInteractor
{

    /**
     * Endpoint for this model.
     *
     * @var string
     */
    protected static $endpoint = '/analytics';

    /**
     * Get the number of users created within the specified periods.
     *
     * @param array $days
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function users(array $days = ['total', '7', '28'])
    {
        $params = $this->formDaysParam($days);

        return $this->requester->request('GET', static::$endpoint . '/users', $params);
    }

    /**
     * Form the days params. Used in multiple places.
     *
     * @param array $days
     * @return array
     */
    private function formDaysParam(array $days)
    {
        $days = implode(',', $days);
        $params = compact('days');

        return $params;
    }

    /**
     * Get the number of sessions created within the specified periods.
     *
     * @param array $days
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function sessions(array $days = ['total', '7', '28'])
    {
        $params = $this->formDaysParam($days);

        return $this->requester->request('GET', static::$endpoint . '/sessions', $params);
    }

    /**
     * Get the number of events created within the specified periods.
     *
     * @param array $days
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function events(array $days = ['total', '7', '28'])
    {
        $params = $this->formDaysParam($days);

        return $this->requester->request('GET', static::$endpoint . '/events', $params);
    }

    /**
     * Get a list of all events triggered, including how many times they've been triggered, and when they were last triggered.
     *
     * @return array
     * @throws \Duffleman\Luno\Exceptions\LunoApiException
     */
    public function listEvents()
    {
        return $this->requester->request('GET', static::$endpoint . '/events/list');
    }

    public function timeline(array $options = [])
    {
        return $this->requester->request('GET', static::$endpoint . '/events/timeline', $options);
    }
}