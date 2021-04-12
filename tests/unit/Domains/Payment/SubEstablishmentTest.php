<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Payment\SubEstablishment;
use Braspag\Gateway\Domains\Document;

class SubEstablishmentTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstanceWithoutArguments()
    {
        $instance = new SubEstablishment();
        $this->assertInstanceOf(SubEstablishment::class, $instance);
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '
        {
            "EstablishmentCode": "123",
            "Mcc": "A1B2",
            "Address": "Avenida Brasil, Campo Grande - Nº 44878",
            "City": "Rio de Janeiro",
            "State": "RJ",
            "PostalCode": "23078001",
            "PhoneNumber": "71912345678",
            "Identity": "44145278000122",
		    "CountryCode": "BRA"
        }
        ';

        $instance = new SubEstablishment();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
