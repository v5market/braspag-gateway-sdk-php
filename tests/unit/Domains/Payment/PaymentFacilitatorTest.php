<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Payment\SubEstablishment;
use Braspag\Gateway\Domains\Payment\PaymentFacilitator;

class PaymentFacilitatorTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstanceWithoutArguments()
    {
        $instance = new PaymentFacilitator();
        $this->assertInstanceOf(PaymentFacilitator::class, $instance);
    }

    /**
     * @test
     */
    public function checkDataEncoded()
    {
        $json = '
        {
            "EstablishmentCode": 300,
            "SubEstablishment": {
                "EstablishmentCode": "123",
                "Mcc": "A1B2",
                "Address": "Avenida Brasil, Campo Grande - Nº 44878",
                "City": "Rio de Janeiro",
                "State": "RJ",
                "PostalCode": "23078001",
                "PhoneNumber": "71912345678"
            }
        }
        ';

        $sub = new SubEstablishment();
        $sub->setEstablishmentCode('123');
        $sub->setMcc('A1B2');
        $sub->setPhoneNumber('71912345678');
        $sub->setAddress(
            'Avenida Brasil, Campo Grande - Nº 44878',
            'Rio de Janeiro',
            'RJ',
            '23078001'
        );

        $instance = new PaymentFacilitator(300, $sub);

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '
        {
            "EstablishmentCode": 300,
            "SubEstablishment": {
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
        }
        ';

        $instance = new PaymentFacilitator();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
