<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\FraudAnalysis\ReplyData;

class ReplyDataTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new ReplyData();
        $this->assertInstanceOf(ReplyData::class, $instance);
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "AddressInfoCode":"COR-BA^MM-BIN",
            "FactorCode":"B^D^R^Z",
            "Score":42,
            "BinCountry":"us",
            "CardIssuer":"FIA CARD SERVICES, N.A.",
            "CardScheme":"VisaCredit",
            "HostSeverity":1,
            "InternetInfoCode":"FREE-EM^RISK-EM",
            "IpRoutingMethod":"Undefined",
            "ScoreModelUsed":"default_lac",
            "CasePriority":3,
            "ProviderTransactionId":"5220688414326697303008"
        }';

        $instance = new ReplyData();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
