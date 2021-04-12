<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Payment\Refund;
use Braspag\Gateway\Constants\Payment\Refund as Constants;

class RefundTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Refund();
        $this->assertInstanceOf(Refund::class, $instance);
    }

    /**
     * @test
     */
    public function checksWhetherToReturnTheCorrectStatusNameBasedOnTheId()
    {
        $instance = new Refund();
        $reflection = new ReflectionClass($instance);
        $reflection_property = $reflection->getProperty('status');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($instance, Constants::SENT);

        $this->assertEquals(Constants::SENT_NAME, $instance->getStatusName());
    }

    /**
     * @test
     */
    public function getterStatusNameMustReturnNullWithNoErrors()
    {
        $instance = new Refund();
        $this->assertNull($instance->getStatusName());
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '
        {
            "Amount": 10000,
            "Status": 3,
            "ReceivedDate": "2017-05-15 16:25:38"
        }
        ';

        $instance = new Refund();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
