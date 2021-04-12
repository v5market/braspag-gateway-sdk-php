<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\FraudAnalysis\Browser;

class BrowserTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Browser();
        $this->assertInstanceOf(Browser::class, $instance);
    }

    /**
     * @test
     */
    public function setAnInvalidEmailShouldReturnAnError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Browser();
        $instance->setEmail('.contact@example.com');
    }

    /**
     * @test
     */
    public function addValidEmailWithoutError()
    {
        $instance = new Browser();
        $instance->setEmail('contact@example.com');
        $this->assertEquals('contact@example.com', $instance->getEmail());
    }

    /**
     * @test
     */
    public function setAnInvalidIPShouldReturnAnError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Browser();
        $instance->setIpAddress('192.168.256.10');
    }

    /**
     * @test
     */
    public function addValidIpWithoutError()
    {
        $instance = new Browser();
        $instance->setIpAddress('192.168.1.1');
        $this->assertEquals('192.168.1.1', $instance->getIpAddress());
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "CookiesAccepted":false,
            "Email":"comprador@braspag.com.br",
            "HostName":"Teste",
            "IpAddress":"127.0.0.1",
            "Type":"Chrome"
        }';

        $instance = new Browser();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
