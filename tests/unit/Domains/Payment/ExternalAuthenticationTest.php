<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Payment\ExternalAuthentication;

class ExternalAuthenticationTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new ExternalAuthentication();
        $this->assertInstanceOf(ExternalAuthentication::class, $instance);
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "Cavv":"AAABB2gHA1B5EFNjWQcDAAAAAAB=",
            "Xid":"Uk5ZanBHcWw2RjRCbEN5dGtiMTB=",
            "Eci":5
        }';

        $instance = new ExternalAuthentication();
        $instance->setCavv('AAABB2gHA1B5EFNjWQcDAAAAAAB=');
        $instance->setXid('Uk5ZanBHcWw2RjRCbEN5dGtiMTB=');
        $instance->setEci(5);

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
