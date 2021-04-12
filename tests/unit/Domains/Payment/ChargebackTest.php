<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Payment\Chargeback;

class ChargebackTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Chargeback();
        $this->assertInstanceOf(Chargeback::class, $instance);
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "Amount": 10000,
            "CaseNumber": "123456",
            "Date": "2017-06-04",
            "ReasonCode": "104",
            "ReasonMessage": "Outras Fraudes - Cartao Ausente",
            "Status": "Received",
            "RawData": "Client did not participate and did not authorize transaction"
        }';

        $instance = new Chargeback();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
