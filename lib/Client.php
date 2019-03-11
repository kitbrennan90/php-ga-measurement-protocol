<?php

namespace GaMeasurementProtocol;

/**
 * Class Client
 * A base client class for interactiving with the GA Measurement Protocol API
 *
 * @package GaMeasurementProtocol
 */
class Client
{
    /**
     * A unique ID to identify a particular client.
     * @var string
     */
    private $clientId;

    /**
     * Sets whether to use debug mode
     * @var bool
     */
    private $debug = false;

    /**
     * Tracking ID / Property ID
     * @var string
     */
    private $trackingId;

    /**
     * Protocol version (1 is the only protocol currently supported by GA)
     * @var int
     */
    private $version = 1;

    /**
     * Client constructor.
     *
     * @param string $trackingId
     * @param string|null $clientId If left null, it will attempt to auto-set from the Google Analytics JS cookies
     */
    public function __construct(string $trackingId, string $clientId = null)
    {
        $this->trackingId = $trackingId;

        if (!$clientId) {
            $clientId = $this->findOrGenerateClientId();
        }
        $this->setClientId($clientId);
    }

    /**
     * Attempts to find a GA client ID from available cookies, otherwise generates a new string for the client ID. This
     * method does NOT set the found or generated client ID.
     *
     * A Google Analytics client ID has the format: `GA1.2.000000000.000000000`
     * A generated client ID has the format: `12345678`
     *
     * @return string
     */
    public function findOrGenerateClientId(): string
    {
        if (isset($_COOKIE["_ga"])) {
            return $_COOKIE["_ga"];
        } else if (isset($_COOKIE["_gid"])) {
            return $_COOKIE["_gid"];
        }

        return $this->generateClientId();
    }

    /**
     * The GA docs call for a UUID v4 string
     * @return string
     */
    private function generateClientId(): string
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),

            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),

            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,

            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,

            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
        );
    }

    /**
     * @return Request
     */
    public function request(): Request
    {
        return new Request($this);
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->clientId;
    }

    /**
     * @param string $clientId
     */
    public function setClientId(string $clientId): void
    {
        $this->clientId = $clientId;
    }

    /**
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->debug;
    }

    /**
     * @param bool $debug
     *
     * @return Client
     */
    public function setDebug(bool $debug): Client
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * @return string
     */
    public function getTrackingId(): string
    {
        return $this->trackingId;
    }

    /**
     * @param string $trackingId
     *
     * @return Client
     */
    public function setTrackingId(string $trackingId): Client
    {
        $this->trackingId = $trackingId;
        return $this;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * Sets the version supported by Google Analytics (currently only "1" is supported)
     *
     * @param int $version
     *
     * @return Client
     */
    public function setVersion(int $version): Client
    {
        $this->version = $version;
        return $this;
    }
}
