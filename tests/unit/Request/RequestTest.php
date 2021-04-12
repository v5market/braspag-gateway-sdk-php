<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Environment;
use Braspag\Gateway\Request\Request;

class RequestTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $env = Environment::sandbox('abc', '123');
        $instance = Request::create($env);

        $this->assertInstanceOf(Curl\Curl::class, $instance);
    }

    /**
     * @test
     */
    public function checkRequestUrlSandbox()
    {
        $env = Environment::sandbox('abc', '123');

        $expectedCapturedSale = 'https://apisandbox.braspag.com.br/v2/sales/{PaymentId}/capture?amount=xxx&serviceTaxAmount=xxx';
        $capturedSale = Request::createUrl(
            $env,
            '{PaymentId}/capture',
            [
                'amount' => 'xxx',
                'serviceTaxAmount' => 'xxx'
            ]
        );

        $this->assertEquals($expectedCapturedSale, $capturedSale);
    }

    /**
     * @test
     */
    public function checkRequestUrlQueryProduction()
    {
        $env = Environment::production('abc', '123');

        $expectedInfo = 'https://apiquery.braspag.com.br/v2/sales/{PaymentId}?';
        $info = Request::createUrl(
            $env,
            '{PaymentId}',
            [],
            true
        );

        $this->assertEquals($expectedInfo, $info);
    }
}
