<?php

namespace GaMeasurementProtocol;

use GaMeasurementProtocol\Enums\HitType;

class Request
{
    /**
     * @var Client
     */
    private $client;

    /**
     * @var string
     */
    private $hitType = HitType::PAGEVIEW;

    /**
     * Array of values to submit with the request to GA, must be in key=>value format.
     * Supported values: https://developers.google.com/analytics/devguides/collection/protocol/v1/parameters
     *
     * @var array|string[]
     */
    private $parameters = [];

    /**
     * Request constructor.
     *
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @return string
     */
    public function getHitType(): string
    {
        return $this->hitType;
    }

    /**
     * @param string $hitType
     *
     * @return Request
     */
    public function setHitType(string $hitType): Request
    {
        $this->hitType = $hitType;
        return $this;
    }

    /**
     * @return array|string[]
     */
    public function getParameters()
    {
        return $this->parameters;
    }

    /**
     * @param array|string[] $parameters
     *
     * @return Request
     */
    public function setParameters($parameters): Request
    {
        $this->parameters = $parameters;
        return $this;
    }

    /**
     * Sends the request to Google Analytics
     */
    public function send(): void
    {
        $requiredParams = [
            'v' => $this->client->getVersion(),
            'tid' => $this->client->getTrackingId(),
            'cid' => $this->client->getClientId(),
            't' => $this->getHitType(),
        ];
        $paramValues = array_merge(
            $requiredParams,
            $this->parameters
        );
        
        $url = $this->getBaseUrl();

        $guzzle = new \GuzzleHttp\Client();
        $result = $guzzle->request('POST', $url, ['form_params' => $paramValues]);

        if ($this->client->isDebug()) {
            var_dump((string) $result->getBody());
        }
    }

    private function getBaseUrl(): string
    {
        if ($this->client->isDebug()) {
            return "https://www.google-analytics.com/debug/collect?";
        }

        return "https://www.google-analytics.com/collect?";
    }
}
