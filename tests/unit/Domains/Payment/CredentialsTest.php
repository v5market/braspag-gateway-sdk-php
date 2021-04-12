<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Payment\Credentials;

class CredentialsTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstanceWithoutArguments()
    {
        $instance = new Credentials();
        $this->assertInstanceOf(Credentials::class, $instance);
    }

    /**
     * @test
     */
    public function newInstanceWithValidArguments()
    {
        $arr = [
            "code",
            "key",
            "username",
            "password",
            "001"
        ];

        $instance = new Credentials(...$arr);

        $this->assertEquals($arr, [
            $instance->getCode(),
            $instance->getKey(),
            $instance->getUsername(),
            $instance->getPassword(),
            $instance->getSignature(),
        ]);
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "Code": "9999999",
            "Key": "D8888888",
            "Password": "LOJA9999999",
            "Username": "#Braspag2018@NOMEDALOJA#"
        }';

        $instance = new Credentials();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
