<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Cart\Item;
use Braspag\Gateway\Constants\Cart\Item as Constants;

class ItemTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Item();
        $this->assertInstanceOf(Item::class, $instance);
    }

    /**
     * @test
     * @dataProvider providerGiftCategory
     */
    public function setValidGiftCategoryWithoutError($arg)
    {
        $instance = new Item();
        $this->assertInstanceOf(Item::class, $instance->setGiftCategory($arg));
        $this->assertEquals($arg, $instance->getGiftCategory());
    }

    /**
     * @test
     * @dataProvider providerImportanceLevel
     */
    public function setValidHostHedgeMustOccurWithoutErrors($arg)
    {
        $instance = new Item();
        $this->assertInstanceOf(Item::class, $instance->setHostHedge($arg));
        $this->assertEquals($arg, $instance->getHostHedge());
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
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
        }';

        $instance = new Item();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    public function providerGiftCategory()
    {
        return [
            [Constants::VALUE_YES],
            [Constants::VALUE_NO],
            [Constants::VALUE_NORMAL],
            [Constants::VALUE_UNDEFINED],
        ];
    }

    public function providerImportanceLevel()
    {
        return [
            [Constants::VALUE_LOW],
            [Constants::VALUE_NORMAL],
            [Constants::VALUE_HIGH],
            [Constants::VALUE_OFF],
        ];
    }
}
