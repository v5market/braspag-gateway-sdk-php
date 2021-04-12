<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Cart\Cart;

class CartTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Cart();
        $this->assertInstanceOf(Cart::class, $instance);
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "IsGift":false,
            "ReturnsAccepted":true,
            "Items":[
               {
                  "GiftCategory":"undefined",
                  "HostHedge":"off",
                  "NonSensicalHedge":"off",
                  "ObscenitiesHedge":"off",
                  "PhoneHedge":"off",
                  "Name":"ItemTeste1",
                  "Quantity":1,
                  "Sku":"20170511",
                  "UnitPrice":10000,
                  "Risk":"high",
                  "TimeHedge":"normal",
                  "Type":"adultcontent",
                  "VelocityHedge":"high"
               },
               {
                  "Name":"ItemTeste2",
                  "Quantity":1,
                  "Sku":"20170512",
                  "UnitPrice":10000
               }
            ]
        }';

        $instance = new Cart();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
