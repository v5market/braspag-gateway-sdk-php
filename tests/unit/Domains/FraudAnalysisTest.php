<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\FraudAnalysis;
use Braspag\Gateway\Domains\Cart\Cart;
use Braspag\Gateway\Domains\Cart\Item as CartItem;
use Braspag\Gateway\Domains\FraudAnalysis\Browser;
use Braspag\Gateway\Domains\FraudAnalysis\Passenger;
use Braspag\Gateway\Domains\FraudAnalysis\ReplyData;
use Braspag\Gateway\Domains\FraudAnalysis\Travel;
use Braspag\Gateway\Constants\FraudAnalysis\Shipping;

class FraudAnalysisTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new FraudAnalysis();
        $this->assertInstanceOf(FraudAnalysis::class, $instance);
    }

    /**
     * @test
     */
    public function definingValidShippingDataCanNotShouldGiveError()
    {
        $instance = new FraudAnalysis();
        $instance->setShipping(Shipping::METHOD_SAME_DAY, 'John Doe', '5571912345678');
        $this->assertEquals([
            'Addressee' => 'John Doe',
            'Method' => Shipping::METHOD_SAME_DAY,
            'Phone' => '5571912345678'
        ], $instance->getShipping());
    }

    /**
     * @test
     */
    public function definingValidShippingDataWithoutPhoneCanNotShouldGiveError()
    {
        $instance = new FraudAnalysis();
        $instance->setShipping(Shipping::METHOD_SAME_DAY, 'John Doe');
        $this->assertEquals([
            'Addressee' => 'John Doe',
            'Method' => Shipping::METHOD_SAME_DAY
        ], $instance->getShipping());
    }

    /**
     * @test
     */
    public function convertObjectToJsonWithoutErrors()
    {
        $browser = new Browser(
            '127.0.0.1',
            'comprador@braspag.com.br',
            'Chrome',
            'Teste',
            false
        );

        $productOne = new CartItem();
        $productOne->setGiftCategory("Undefined");
        $productOne->setHostHedge("Off");
        $productOne->setNonSensicalHedge("Off");
        $productOne->setObscenitiesHedge("Off");
        $productOne->setPhoneHedge("Off");
        $productOne->setName("ItemTeste1");
        $productOne->setQuantity(1);
        $productOne->setSku("20170511");
        $productOne->setUnitPrice(10000);
        $productOne->setRisk("High");
        $productOne->setTimeHedge("Normal");
        $productOne->setType("AdultContent");
        $productOne->setVelocityHedge("High");

        $productTwo = new CartItem();
        $productTwo->setGiftCategory("Undefined");
        $productTwo->setHostHedge("Off");
        $productTwo->setNonSensicalHedge("Off");
        $productTwo->setObscenitiesHedge("Off");
        $productTwo->setPhoneHedge("Off");
        $productTwo->setName("ItemTeste2");
        $productTwo->setQuantity(1);
        $productTwo->setSku("20170512");
        $productTwo->setUnitPrice(10000);
        $productTwo->setRisk("High");
        $productTwo->setTimeHedge("Normal");
        $productTwo->setType("AdultContent");
        $productTwo->setVelocityHedge("High");

        $cart = new Cart($productOne, $productTwo);
        $cart->setIsGift(false);
        $cart->setReturnsAccepted(true);

        $passenger = new Passenger();
        $passenger->setName('Passenger Test');
        $passenger->setIdentity('212424808');
        $passenger->setStatus('Gold');
        $passenger->setRating('Adult');
        $passenger->setEmail('email@mail.com');
        $passenger->setPhone('64991681074');
        $passenger->addTravelLegs('AMS', 'GIG');

        $travel = new Travel();
        $travel->setJourneyType('OneWayTrip');
        $travel->setDepartureTime(DateTime::createFromFormat('Y-m-d H:i', '2018-01-09 18:00'));
        $travel->addPassenger($passenger);

        $instance = new FraudAnalysis();
        $instance->setSequence('AnalyseFirst');
        $instance->setSequenceCriteria('Always');
        $instance->setProvider('Cybersource');
        $instance->setCaptureOnLowRisk(false);
        $instance->setVoidOnHighRisk(false);
        $instance->setTotalOrderAmount(10000);
        $instance->setFingerPrintId('074c1ee676ed4998ab66491013c565e2');
        $instance->setMerchantDefinedFields(2, '100');
        $instance->setMerchantDefinedFields(4, 'Web');
        $instance->setMerchantDefinedFields(9, 'SIM');
        $instance->setShipping(
            'LowCost',
            'João das Couves',
            '551121840540'
        );
        $instance->setBrowser($browser);
        $instance->setCart($cart);
        $instance->setTravel($travel);

        $expectedJson = '{
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
              "Items": [
                {
                  "GiftCategory": "undefined",
                  "HostHedge": "off",
                  "NonSensicalHedge": "off",
                  "ObscenitiesHedge": "off",
                  "PhoneHedge": "off",
                  "Name": "ItemTeste1",
                  "Quantity": 1,
                  "Sku": "20170511",
                  "UnitPrice": 10000,
                  "Risk": "high",
                  "TimeHedge": "normal",
                  "Type": "adultcontent",
                  "VelocityHedge": "high"
                },
                {
                  "GiftCategory": "undefined",
                  "HostHedge": "off",
                  "NonSensicalHedge": "off",
                  "ObscenitiesHedge": "off",
                  "PhoneHedge": "off",
                  "Name": "ItemTeste2",
                  "Quantity": 1,
                  "Sku": "20170512",
                  "UnitPrice": 10000,
                  "Risk": "high",
                  "TimeHedge": "normal",
                  "Type": "adultcontent",
                  "VelocityHedge": "high"
                }
              ]
            },
            "MerchantDefinedFields": [
              {
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
              "Passengers": [
                {
                  "Name": "Passenger Test",
                  "Identity": "212424808",
                  "Status": "gold",
                  "Rating": "adult",
                  "Email": "email@mail.com",
                  "Phone": "5564991681074",
                  "TravelLegs": [
                    {
                      "Origin": "AMS",
                      "Destination": "GIG"
                    }
                  ]
                }
              ]
            }
        }';

        $this->assertJsonStringEqualsJsonString($expectedJson, json_encode($instance));
    }

    /**
     * @test
     */
    public function convertJsonToObjectWithoutErrors()
    {
        $json = '
        {
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
              "Items": [
                {
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
            "MerchantDefinedFields": [
              {
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
              "Passengers": [
                {
                  "Name": "Passenger Test",
                  "Identity": "212424808",
                  "Status": "Gold",
                  "Rating": "Adult",
                  "Email": "email@mail.com",
                  "Phone": "5564991681074",
                  "TravelLegs": [
                    {
                      "Origin": "AMS",
                      "Destination": "GIG"
                    }
                  ]
                }
              ]
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
        }
        ';

        $instance = new FraudAnalysis();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
