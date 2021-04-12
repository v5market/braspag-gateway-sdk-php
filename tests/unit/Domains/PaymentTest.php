<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Payment;
use Braspag\Gateway\Domains\Payment\Credentials;
use Braspag\Gateway\Domains\PaymentMethod\CreditCard;
use Braspag\Gateway\Domains\PaymentMethod\DebitCard;
use Braspag\Gateway\Domains\PaymentMethod\Boleto;
use Braspag\Gateway\Domains\PaymentMethod\EletronicTransfer;

class PaymentTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Payment();
        $this->assertInstanceOf(Payment::class, $instance);
    }

    /**
     * @test
     */
    public function defineMultipleExtraDataWithoutError()
    {
        $instance = new Payment();
        $instance->setExtraDataCollection("custom_id", "Customer_202008121");

        $this->assertEquals([
            [
                "Name" => "custom_id",
                "Value" => "Customer_202008121"
            ]
        ], $instance->getExtraDataCollection());
    }

    /**
     * @test
     */
    public function whenAddingACreditCardThePaymentTypeMustReturnCreditCard()
    {
        $instance = new Payment();
        $instance->setPaymentMethod(new CreditCard());
        $this->assertEquals('CreditCard', $instance->getType());
    }

    /**
     * @test
     */
    public function whenAddingADebitCardThePaymentTypeMustReturnDebitCard()
    {
        $instance = new Payment();
        $instance->setPaymentMethod(new DebitCard());
        $this->assertEquals('DebitCard', $instance->getType());
    }

    /**
     * @test
     */
    public function whenAddingABoletoThePaymentTypeMustReturnBoleto()
    {
        $instance = new Payment();
        $instance->setPaymentMethod(new Boleto());
        $this->assertEquals('Boleto', $instance->getType());
    }

    /**
     * @test
     */
    public function whenAddingAETFThePaymentTypeMustReturnEletronicTransfer()
    {
        $instance = new Payment();
        $instance->setPaymentMethod(new EletronicTransfer());
        $this->assertEquals('EletronicTransfer', $instance->getType());
    }

    /**
     * @test
     */
    public function addingAnInvalidCurrencyShouldReturnAnError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->setCurrency('REAL');
    }

    /**
     * @test
     */
    public function addingAnValidCurrencyWithoutError()
    {
        $instance = new Payment();
        $instance->setCurrency('BRL');
        $this->assertEquals('BRL', $instance->getCurrency());
    }

    /**
     * @test
     */
    public function definingCountryWithMoreThanThreeCharactersShouldGiveError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Payment();
        $instance->setCountry('Brasil');
    }

    /**
     * @test
     */
    public function populateDataCreditCard()
    {
        $json = '
        {
            "Provider": "Simulado",
            "Type": "CreditCard",
            "Amount": 10000,
            "Currency": "BRL",
            "Country": "BRA",
            "Installments": 1,
            "Interest": "ByMerchant",
            "Capture": true,
            "Authenticate": false,
            "Recurrent": false,
            "SoftDescriptor": "Mensagem",
            "DoSplit": false,
            "CreditCard": {
                "CardNumber": "4551870000000181",
                "Holder": "Nome do Portador",
                "ExpirationDate": "12/2021",
                "SecurityCode": "123",
                "Brand": "Visa",
                "SaveCard": "false"
            },
            "Credentials": {
                "Code": "9999999",
                "Key": "D8888888",
                "Password": "LOJA9999999",
                "Username": "#Braspag2018@NOMEDALOJA#",
                "Signature": "001"
            },
            "ExtraDataCollection": [{
                "Name": "NomeDoCampo",
                "Value": "ValorDoCampo"
            }],
            "FraudAnalysis": {
                "Sequence": "AnalyseFirst",
                "SequenceCriteria": "Always",
                "Provider": "Cybersource",
                "CaptureOnLowRisk": false,
                "VoidOnHighRisk": false,
                "TotalOrderAmount": 10000,
                "FingerPrintId": "074c1ee676ed4998ab66491013c565e2",
                "Browser": {
                    "CookiesAccepted": false,
                    "Email": "comprador@braspag.com.br",
                    "HostName": "Teste",
                    "IpAddress": "127.0.0.1",
                    "Type": "Chrome"
                },
                "Cart": {
                    "IsGift": false,
                    "ReturnsAccepted": true,
                    "Items": [{
                            "GiftCategory": "Undefined",
                            "HostHedge": "Off",
                            "NonSensicalHedge": "Off",
                            "ObscenitiesHedge": "Off",
                            "PhoneHedge": "Off",
                            "Name": "ItemTeste1",
                            "Quantity": 1,
                            "Sku": "20170511",
                            "UnitPrice": 10000,
                            "Risk": "High",
                            "TimeHedge": "Normal",
                            "Type": "AdultContent",
                            "VelocityHedge": "High"
                        },
                        {
                            "GiftCategory": "Undefined",
                            "HostHedge": "Off",
                            "NonSensicalHedge": "Off",
                            "ObscenitiesHedge": "Off",
                            "PhoneHedge": "Off",
                            "Name": "ItemTeste2",
                            "Quantity": 1,
                            "Sku": "20170512",
                            "UnitPrice": 10000,
                            "Risk": "High",
                            "TimeHedge": "Normal",
                            "Type": "AdultContent",
                            "VelocityHedge": "High"
                        }
                    ]
                },
                "MerchantDefinedFields": [{
                        "Id": 2,
                        "Value": "100"
                    },
                    {
                        "Id": 4,
                        "Value": "Web"
                    },
                    {
                        "Id": 9,
                        "Value": "SIM"
                    }
                ],
                "Shipping": {
                    "Addressee": "João das Couves",
                    "Method": "LowCost",
                    "Phone": "551121840540"
                },
                "Travel": {
                    "JourneyType": "OneWayTrip",
                    "DepartureTime": "2018-01-09 18:00",
                    "Passengers": [{
                        "Name": "Passenger Test",
                        "Identity": "212424808",
                        "Status": "Gold",
                        "Rating": "Adult",
                        "Email": "email@mail.com",
                        "Phone": "5564991681074",
                        "TravelLegs": [{
                            "Origin": "AMS",
                            "Destination": "GIG"
                        }]
                    }]
                },
                "Id": "0e4d0a3c-e424-4fa5-a573-4eabbd44da42",
                "Status": 1,
                "FraudAnalysisReasonCode": 100,
                "ReplyData": {
                    "AddressInfoCode": "COR-BA^MM-BIN",
                    "FactorCode": "B^D^R^Z",
                    "Score": 42,
                    "BinCountry": "us",
                    "CardIssuer": "FIA CARD SERVICES, N.A.",
                    "CardScheme": "VisaCredit",
                    "HostSeverity": 1,
                    "InternetInfoCode": "FREE-EM^RISK-EM",
                    "IpRoutingMethod": "Undefined",
                    "ScoreModelUsed": "default_lac",
                    "CasePriority": 3,
                    "ProviderTransactionId": "5220688414326697303008"
                }
            },
            "PaymentId": "c374099e-c474-4916-9f5c-f2598fec2925",
            "ProofOfSale": "20170510053219433",
            "AcquirerTransactionId": "0510053219433",
            "AuthorizationCode": "936403",
            "ReceivedDate": "2017-05-10 17:32:19",
            "CapturedAmount": 10000,
            "CapturedDate": "2017-05-10 17:32:19",
            "ReasonCode": 0,
            "ReasonMessage": "Successful",
            "Status": 2,
            "ProviderReturnCode": "6",
            "ProviderReturnMessage": "Operation Successful"
        }
        ';

        $instance = new Payment();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
        $this->assertEquals('CreditCard', $instance->getType());
    }

    /**
     * @test
     */
    public function populateDataDebitCard()
    {
        $json = '
        {
            "DebitCard": {
              "CardNumber": "455187******0181",
              "Holder": "Nome do Portador",
              "ExpirationDate": "12/2021",
              "SaveCard": false,
              "Brand": "Visa"
            },
            "AuthenticationUrl": "https://qasecommerce.cielo.com.br/web/index.cbmp?id=13fda1da8e3d90d3d0c9df8820b96a7f",
            "AcquirerTransactionId": "10069930690009D366FA",
            "PaymentId": "21423fa4-6bcf-448a-97e0-e683fa2581ba",
            "Type": "DebitCard",
            "Amount": 10000,
            "ReceivedDate": "2017-05-11 15:19:58",
            "Currency": "BRL",
            "Country": "BRA",
            "Provider": "Cielo",
            "ReturnUrl": "http://www.braspag.com.br",
            "ReasonCode": 9,
            "ReasonMessage": "Waiting",
            "Status": 0,
            "ProviderReturnCode": "0"
        }
        ';

        $instance = new Payment();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
        $this->assertEquals('DebitCard', $instance->getType());
        $this->assertEquals(
            'https://qasecommerce.cielo.com.br/web/index.cbmp?id=13fda1da8e3d90d3d0c9df8820b96a7f',
            $instance->getAuthenticationUrl()
        );
        $this->assertEquals(
            '2017-05-11 15:19:58',
            $instance->getReceivedDate()->format('Y-m-d H:i:s')
        );
    }

    /**
     * @test
     */
    public function populateDataBoleto()
    {
        $json = '
        {
            "Instructions": "Aceitar somente até a data de vencimento.",
            "ExpirationDate": "2017-12-31",
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
            "DaysToInterest": 1
        }
        ';

        $instance = new Payment();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
        $this->assertEquals('Boleto', $instance->getType());
        $this->assertEquals(
            "https://homologacao.pagador.com.br/post/pagador/reenvia.asp/d24b0aa4-21c9-449d-b85c-6279333f070f",
            $instance->getPaymentMethod()->getUrl()
        );
    }

    /**
     * @test
     */
    public function populateDataEletronicTransfer()
    {
        $json = '
        {
            "Provider":"Bradesco",
            "Type":"EletronicTransfer",
            "Amount":10000,
            "ReturnUrl":"http://www.braspag.com.br",
            "Beneficiary":
                {
                "Bank":"Bradesco"
            },
            "Shopper":{
                "Branch":"1669",
                "Account":"19887-5"
            },
            "Url": "https://xxx.xxxxxxx.xxx.xx/post/EletronicTransfer/Redirect/{PaymentId}",
            "PaymentId": "765548b6-c4b8-4e2c-b9b9-6458dbd5da0a",
            "Amount": 10000,
            "ReceivedDate": "2015-06-25 09:37:55",
            "Currency": "BRL",
            "Country": "BRA",
            "Provider": "Bradesco",
            "ReturnUrl": "http://www.braspag.com.br",
            "ReasonCode": 0,
            "ReasonMessage": "Successful",
            "Status": 0
        }
        ';

        $instance = new Payment();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
        $this->assertEquals('EletronicTransfer', $instance->getType());
        $this->assertEquals(
            'https://xxx.xxxxxxx.xxx.xx/post/EletronicTransfer/Redirect/{PaymentId}',
            $instance->getPaymentMethod()->getUrl()
        );
    }

    /**
     * @test
     */
    public function populateDataPix()
    {
        $json = '
        {
            "PaymentId":"1997be4d-694a-472e-98f0-e7f4b4c8f1e7",
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

        $instance = new Payment();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
        $this->assertEquals('Pix', $instance->getType());
        $this->assertEquals(
            '00020101021226880014br.gov.bcb.pix2566qrcodes-h.cielo.com.br/pix-qr/d05b1a34-ec52-4201-ba1e-d3cc2a43162552040000530398654041.005802BR5918Merchant Teste HML6009Sao Paulo62120508000101296304031C',
            $instance->getPaymentMethod()->getQrCodeString()
        );
    }

    /**
     * @test
     */
    public function createSaleWithCreditCardWithValidArguments()
    {
        $json = '
        {
            "Provider":"Simulado",
            "Type":"CreditCard",
            "Amount":10000,
            "Currency":"BRL",
            "Country":"BRA",
            "Installments":1,
            "Interest":"ByMerchant",
            "Capture":true,
            "Authenticate":false,
            "Recurrent":false,
            "SoftDescriptor":"Mensagem",
            "DoSplit":false,
            "CreditCard":{
               "CardNumber":"4551870000000181",
               "Holder":"Nome do Portador",
               "ExpirationDate":"12/2021",
               "SecurityCode":"123",
               "Brand":"visa",
               "SaveCard":false,
               "Alias":"",
               "CardOnFile":{
                  "Usage": "Used",
                  "Reason":"Unscheduled"
               }
            },
            "Credentials":{
               "Code":"9999999",
               "Key":"D8888888",
               "Password":"LOJA9999999",
               "Username":"#Braspag2018@NOMEDALOJA#",
               "Signature":"001"
            },
            "ExtraDataCollection":[
               {
                  "Name":"NomeDoCampo",
                  "Value":"ValorDoCampo"
               }
            ]
        }
        ';

        $creditCard = new CreditCard();
        $creditCard->setNumber('4551870000000181');
        $creditCard->setHolder('Nome do Portador');
        $creditCard->setExpirationDate('12/2021');
        $creditCard->setSecurityCode('123');
        $creditCard->setBrand('Visa');
        $creditCard->setSaveCard(false);
        $creditCard->setAlias('');
        $creditCard->setCardOnFileUsage('Used');
        $creditCard->setCardOnFileReason('Unscheduled');

        $credentials = new Credentials();
        $credentials->setCode('9999999');
        $credentials->setKey('D8888888');
        $credentials->setPassword('LOJA9999999');
        $credentials->setUsername('#Braspag2018@NOMEDALOJA#');
        $credentials->setSignature('001');

        $instance = new Payment();
        $instance->setProvider('Simulado');
        $instance->setAmount(10000);
        $instance->setCurrency('BRL');
        $instance->setInstallments(1);
        $instance->setInterest('ByMerchant');
        $instance->setCapture(true);
        $instance->setAuthenticate(false);
        $instance->setRecurrent(false);
        $instance->setSoftDescriptor('Mensagem');
        $instance->setDoSplit(false);
        $instance->setPaymentMethod($creditCard);
        $instance->setCredentials($credentials);
        $instance->setExtraDataCollection('NomeDoCampo', 'ValorDoCampo');
        $instance->setCountry('BRA');

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function createSaleWithBoletoWithValidArguments()
    {
        $json = '
        {
            "Provider":"Simulado",
            "Type":"Boleto",
            "Amount":10000,
            "BoletoNumber":"2017091101",
            "Assignor": "Empresa Teste",
            "Demonstrative": "Desmonstrative Teste",
            "ExpirationDate": "2030-12-31",
            "Identification": "00000000000191",
            "Instructions": "Aceitar somente até a data de vencimento.",
            "DaysToFine": 1,
            "FineRate": 10.00000,
            "FineAmount": 1000,
            "DaysToInterest": 1,
            "InterestRate": 5.00000,
            "InterestAmount": 500
        }
        ';

        $boleto = new Boleto();
        $boleto->setBoletoNumber('2017091101');
        $boleto->setAssignor('Empresa Teste');
        $boleto->setDemonstrative('Desmonstrative Teste');
        $boleto->setExpirationDate(DateTime::createFromFormat('Y-m-d', '2030-12-31'));
        $boleto->setIdentification('00.000.000/0001-91');
        $boleto->setInstructions('Aceitar somente até a data de vencimento.');
        $boleto->setDaysToFine(1);
        $boleto->setFineRate(10.00000);
        $boleto->setFineAmount(1000);
        $boleto->setDaysToInterest(1);
        $boleto->setInterestRate(5.00000);
        $boleto->setInterestAmount(500);

        $instance = new Payment();
        $instance->setProvider('Simulado');
        $instance->setAmount(10000);
        $instance->setPaymentMethod($boleto);

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function popularDataWhenCapturingOrdersViaStoreIdentifier()
    {
        $json = '{
            "PaymentId": "5fb4d606-bb63-4423-a683-c966e15399e8",
            "ReceveidDate": "2015-04-06T10:13:39.42"
        }';

        $instance = new Payment();
        $instance->populate(json_decode($json));

        $this->assertEquals('5fb4d606-bb63-4423-a683-c966e15399e8', $instance->getPaymentId());
        $this->assertEquals('2015-04-06 10:13:39', $instance->getReceveidDate()->format('Y-m-d H:i:s'));
    }
}
