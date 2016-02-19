<?php

namespace Duffleman\Luno\Interactors;

class AnalyticsInteractor extends BaseInteractor
{

    protected static $endpoint = '/analytics';

    public function users(array $days = ['total', '7', '28']): array
    {
        $params = $this->formDaysParam($days);

        return $this->requester->request('GET', static::$endpoint . '/users', $params);
    }

    public function sessions(array $days = ['total', '7', '28']): array
    {
        $params = $this->formDaysParam($days);

        return $this->requester->request('GET', static::$endpoint . '/sessions', $params);
    }

    public function events(array $days = ['total', '7', '28']): array
    {
        $params = $this->formDaysParam($days);

        return $this->requester->request('GET', static::$endpoint . '/events', $params);
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
}