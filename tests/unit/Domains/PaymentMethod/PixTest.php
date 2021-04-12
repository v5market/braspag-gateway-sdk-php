<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\PaymentMethod\Pix;

class PixTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Pix();
        $this->assertInstanceOf(Pix::class, $instance);
    }

    /**
     * @test
     */
    public function convertJsonToObjectWithoutErrors()
    {
        $json = '
        {
            "Paymentid":"1997be4d-694a-472e-98f0-e7f4b4c8f1e7",
            "Type":"Pix",
            "Provider":"Cielo30",
            "AcquirerTransactionId":"86c200c7-7cdf-4375-92dd-1f62dfa846ad",
            "ProofOfSale":"123456",
            "QrCodeBase64Image":"rfhviy64ak+zse18cwcmtg==",
            "QrCodeString":"00020101021226880014br.gov.bcb.pix2566qrcodes-h.cielo.com.br/pix-qr/d05b1a34-ec52-4201-ba1e-d3cc2a43162552040000530398654041.005802BR5918Merchant Teste HML6009Sao Paulo62120508000101296304031C",
            "Amount":100,
            "ReceivedDate":"2020-10-15 18:53:20",
            "Status":12,
            "ProviderReturnCode":"0",
            "ProviderReturnMessage":"Pix gerado com sucesso"
        }
        ';

        $instance = new Pix();
        $instance->populate(json_decode($json));

        $this->assertEquals(
            [
                'rfhviy64ak+zse18cwcmtg==',
                '00020101021226880014br.gov.bcb.pix2566qrcodes-h.cielo.com.br/pix-qr/d05b1a34-ec52-4201-ba1e-d3cc2a43162552040000530398654041.005802BR5918Merchant Teste HML6009Sao Paulo62120508000101296304031C'
            ],
            [
                $instance->getQrCodeBase64Image(),
                $instance->getQrCodeString(),
            ]
        );
    }
}
