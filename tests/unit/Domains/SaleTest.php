<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Sale;

class SaleTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Sale();
        $this->assertInstanceOf(Sale::class, $instance);
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '
        {
            "MerchantOrderId": "2017091101",
            "Customer": {
                "Name": "Nome do Comprador",
                "Identity": "30961985003",
                "IdentityType": "cpf",
                "Address": {
                    "Street": "Alameda Xingu",
                    "Number": "512",
                    "Complement": "27 andar",
                    "ZipCode": "12345987",
                    "City": "São Paulo",
                    "State": "SP",
                    "Country": "BRA",
                    "District":"Alphaville"
                }
            },
            "Payment": {
                "Instructions": "Aceitar somente até a data de vencimento.",
                "ExpirationDate": "2030-12-31",
                "Demonstrative": "Desmonstrative Teste",
                "Url": "https://homologacao.pagador.com.br/post/pagador/reenvia.asp/d24b0aa4-21c9-449d-b85c-6279333f070f",
                "BoletoNumber": "2017091101",
                "BarCodeNumber": "00091739000000100000494250000000263400656560",
                "DigitableLine": "00090.49420 50000.000260 34006.565609 1 73900000010000",
                "Assignor": "Empresa Teste",
                "Address": "Av. Brigadeiro Faria Lima, 160",
                "Identification": "12346578909",
                "PaymentId": "d24b0aa4-21c9-449d-b85c-6279333f070f",
                "Type": "Boleto",
                "Amount": 10000,
                "ReceivedDate": "2017-05-11 16:42:55",
                "Currency": "BRL",
                "Country": "BRA",
                "Provider": "Simulado",
                "ReasonCode": 0,
                "ReasonMessage": "Successful",
                "Status": 1,
                "InterestAmount": 1,
                "FineAmount": 5,
                "DaysToFine": 1,
                "DaysToInterest": 1,
                "ExternalAuthentication":{
                    "Cavv":"AAABB2gHA1B5EFNjWQcDAAAAAAB=",
                    "Xid":"Uk5ZanBHcWw2RjRCbEN5dGtiMTB=",
                    "Eci":"5"
                }
            }
        }
        ';

        $instance = new Sale();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
        $this->assertJsonStringEqualsJsonString($json, $instance->getResponseRaw());
        $this->assertEquals('2017091101', $instance->getPayment()->getPaymentMethod()->getBoletoNumber());
        $this->assertEquals('d24b0aa4-21c9-449d-b85c-6279333f070f', $instance->getPayment()->getPaymentId());
        $this->assertEquals('Boleto', $instance->getPayment()->getType());
        $this->assertEquals(
            $instance->getPayment()->getStatus(),
            $instance->getPayment()->getPaymentMethod()->getStatus(),
        );
    }
}
