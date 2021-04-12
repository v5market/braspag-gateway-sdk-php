<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\PaymentMethod\EletronicTransfer;

class EletronicTransferTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new EletronicTransfer();
        $this->assertInstanceOf(EletronicTransfer::class, $instance);
    }

    /**
     * @test
     */
    public function checkIfMethodGetShopperBranchReturnsNull()
    {
        $instance = new EletronicTransfer();
        $this->assertNull($instance->getShopperBranch());

        $instance->setShopperBranch('1669');
        $this->assertEquals('1669', $instance->getShopperBranch());
    }

    /**
     * @test
     */
    public function convertObjectToJsonWithoutErrors()
    {
        $json = '
        {
            "Beneficiary": {
                "Bank":"Bradesco"
            },
            "Shopper":{
                "Branch":"1669",
                "Account":"19887-5"
            }
        }
        ';

        $instance = new EletronicTransfer();
        $instance->setBeneficiaryBank('Bradesco');
        $instance->setShopperBranch('1669');
        $instance->setShopperAccount('19887-5');

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function convertJsonToObjectWithoutErrors()
    {
        $json = '
        {
            "Beneficiary": {
                "Bank":"Bradesco"
            },
            "Shopper":{
                "Branch":"1669",
                "Account":"19887-5"
            },
            "Url": "https://xxx.xxxxxxx.xxx.xx/post/EletronicTransfer/Redirect/{PaymentId}",
            "Status": 0
        }
        ';

        $instance = new EletronicTransfer();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
