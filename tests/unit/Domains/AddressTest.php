<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Address;

class AddressTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Address();
        $this->assertInstanceOf(Address::class, $instance);
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "Street": "Alameda Xingu",
            "Number": "512",
            "Complement": "27 andar",
            "ZipCode": "12345987",
            "City": "São Paulo",
            "State": "SP",
            "Country": "BR",
            "District": "Alphaville"
        }';

        $instance = new Address();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
