<?php

use GaMeasurementProtocol\Client;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private $testTrackingId = 'GA-123456-1';

    private $testGaId = 'GA1.2.000000000.000000000';

    private $testGidId = 'GA1.2.999999999.999999999';

    protected function tearDown(): void
    {
        parent::tearDown();
        $_COOKIE = [];
    }

    public function testGenerateClient()
    {
        $client = new Client($this->testTrackingId);

        $this->assertEquals($this->testTrackingId, $client->getTrackingId());
        $this->assertNotNull($client->getClientId(), 'A client ID should be autoset');
    }

    public function testClientUsesClientIdInGaCookie()
    {
        $_COOKIE['_ga'] = $this->testGaId;
        $client = new Client($this->testTrackingId);

        $this->assertEquals($this->testGaId, $client->getClientId());
        $this->assertNotEquals($this->testGidId, $client->getClientId());
    }

    public function testClientUsesClientIdInGidCookie()
    {
        $_COOKIE['_gid'] = $this->testGidId;
        $client = new Client($this->testTrackingId);

        $this->assertEquals($this->testGidId, $client->getClientId());
        $this->assertNotEquals($this->testGaId, $client->getClientId());
    }

    public function testGaIdHasPriorityOverGid()
    {
        $_COOKIE['_ga'] = $this->testGaId;
        $_COOKIE['_gid'] = $this->testGidId;
        $client = new Client($this->testTrackingId);

        $this->assertEquals($this->testGaId, $client->getClientId());
        $this->assertNotEquals($this->testGidId, $client->getClientId());
    }

    public function testRequestMethodReturnRequest()
    {
        $client = new Client($this->testTrackingId);
        $request = $client->request();

        $this->assertInstanceOf(\GaMeasurementProtocol\Request::class, $request);
    }
}
