<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Document;

class DocumentTest extends BraspagGatewayTestCase
{
    /**
     * @test
     * @dataProvider providerCpfValid
     */
    public function newInstanceCpfWithValidArguments($arg)
    {
        $expected = preg_replace('/\D/', '', $arg);
        $instance = Document::cpf($arg);
        $this->assertEquals($arg, $instance->getValue());
    }

    /**
     * @test
     * @dataProvider providerCpfInvalid
     */
    public function newInstanceCpfWithInvalidArguments($arg)
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = Document::cpf($arg);
    }

    public function providerCpfValid(): array
    {
        return [
            ["448.957.230-11"],
            ["448.957.23011"],
            ["44895723011"]
        ];
    }

    public function providerCpfInvalid(): array
    {
        return [
            ["448.957.230-1i"],
            ["448.957.23001"],
            ["4489572301"]
        ];
    }
}
