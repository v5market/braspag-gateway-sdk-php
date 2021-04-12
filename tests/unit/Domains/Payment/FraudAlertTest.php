<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Payment\FraudAlert;

class AlertFraudTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new FraudAlert();
        $this->assertInstanceOf(FraudAlert::class, $instance);
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '
        {
            "Date": "2017-05-20",
            "ReasonMessage": "Uso Ind Numeração",
            "IncomingChargeback": false
        }
        ';

        $instance = new FraudAlert();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
