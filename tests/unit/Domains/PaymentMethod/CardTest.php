<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\PaymentMethod\AbstractCard;
use Braspag\Gateway\Domains\PaymentMethod\CreditCard;
use Braspag\Gateway\Domains\PaymentMethod\DebitCard;
use Braspag\Gateway\Constants\PaymentMethod\CreditCardBrand;
use Braspag\Gateway\Constants\PaymentMethod\DebitCardBrand;
use Braspag\Gateway\Constants\PaymentMethod\CardOnFile;

class CardTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstanceOfCreditCard()
    {
        $instance = new CreditCard();
        $this->assertInstanceOf(AbstractCard::class, $instance);
        $this->assertInstanceOf(CreditCard::class, $instance);
    }

    /**
     * @test
     */
    public function newInstanceOfDebitCard()
    {
        $instance = new DebitCard();
        $this->assertInstanceOf(AbstractCard::class, $instance);
        $this->assertInstanceOf(DebitCard::class, $instance);
    }

    /**
     * @test
     */
    public function definesValidCardFlag()
    {
        $instance = new CreditCard();
        $instance->setBrand(CreditCardBrand::MASTER);
        $this->assertEquals(CreditCardBrand::MASTER, $instance->getBrand());
    }

    /**
     * @test
     */
    public function definesValidDebitCardFlag()
    {
        $instance = new DebitCard();
        $instance->setBrand(DebitCardBrand::VISA);
        $this->assertEquals(DebitCardBrand::VISA, $instance->getBrand());
    }

    /**
     * @test
     */
    public function definesValidCardOnFile()
    {
        $instance = new CreditCard();
        $instance->setCardOnFileUsage(CardOnFile::USAGE_FIRST);
        $this->assertEquals(CardOnFile::USAGE_FIRST, $instance->getCardOnFileUsage());
    }

    /**
     * @test
     */
    public function definesInvalidCardOnFile()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreditCard();
        $instance->setCardOnFileUsage('invalid');
    }

    /**
     * @test
     */
    public function checkInvalidCardOnFileReason()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreditCard();
        $instance->setCardOnFileReason('invalid');
    }

    /**
     * @test
     */
    public function setInvalidExpirationDateShouldGiveError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new CreditCard();
        $instance->setExpirationDate('07/20');
    }

    /**
     * @test
     */
    public function setValidExpirationDate()
    {
        $instance = new CreditCard();
        $instance->setExpirationDate('07/2020');
        $this->assertEquals('07/2020', $instance->getExpirationDate());
    }

    /**
     * @test
     */
    public function convertObjectDebitCardToJsonWithoutErrors()
    {
        $json = '
        {
            "CardNumber":"4551870000000181",
            "Holder":"Nome do Portador",
            "ExpirationDate":"07/3991",
            "SecurityCode":"123",
            "Brand":"visa"
        }
        ';

        $instance = new DebitCard();
        $instance->setNumber("4551870000000181");
        $instance->setHolder("Nome do Portador");
        $instance->setExpirationDate("07/3991");
        $instance->setSecurityCode("123");
        $instance->setBrand("Visa");

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function convertJsonToDebitCardObjectWithoutErrors()
    {
        $json = '
        {
            "CardNumber":"4551870000000181",
            "Holder":"Nome do Portador",
            "ExpirationDate":"07/3991",
            "SecurityCode":"123",
            "Brand":"visa"
        }
        ';

        $instance = new DebitCard();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function convertObjectCreditCardToJsonWithoutErrors()
    {
        $json = '
        {
            "CardNumber":"4551870000000181",
            "Holder":"Nome do Portador",
            "ExpirationDate":"07/3991",
            "SecurityCode":"123",
            "Brand":"visa",
            "SaveCard":false,
            "Alias":"",
            "CardOnFile":{
               "Usage": "Used",
               "Reason":"Unscheduled"
            }
        }
        ';

        $instance = new CreditCard();
        $instance->setNumber("4551870000000181");
        $instance->setHolder("Nome do Portador");
        $instance->setExpirationDate("07/3991");
        $instance->setSecurityCode("123");
        $instance->setBrand("Visa");
        $instance->setSaveCard(false);
        $instance->setAlias('');
        $instance->setCardOnFileUsage('Used');
        $instance->setCardOnFileReason('Unscheduled');

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function convertJsonToObjectCreditCardWithoutErrors()
    {
        $json = '
        {
            "CardNumber":"4551870000000181",
            "Holder":"Nome do Portador",
            "ExpirationDate":"07/3991",
            "SecurityCode":"123",
            "Brand":"visa",
            "SaveCard":false,
            "Alias":"",
            "CardOnFile":{
               "Usage": "Used",
               "Reason":"Unscheduled"
            },
            "CardToken": "f031d3e2-7a7d-446a-a8bc-5e906a8d74d7"
        }
        ';

        $instance = new CreditCard();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function createPaymentWithCardToken()
    {
        $json = '
        {
            "CardToken": "f031d3e2-7a7d-446a-a8bc-5e906a8d74d7",
            "SecurityCode": "123",
            "Brand": "visa"
        }
        ';

        $instance = new CreditCard();
        $instance->setCardToken("f031d3e2-7a7d-446a-a8bc-5e906a8d74d7");
        $instance->setSecurityCode("123");
        $instance->setBrand("visa");

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }

    /**
     * @test
     */
    public function createPaymentWithCardAlias()
    {
        $json = '
        {
            "CardToken": "f031d3e2-7a7d-446a-a8bc-5e906a8d74d7",
            "SecurityCode": "123",
            "Brand": "visa",
            "Alias": "PrimeiroTokenASerGerado"
        }
        ';

        $instance = new CreditCard();
        $instance->setCardToken("f031d3e2-7a7d-446a-a8bc-5e906a8d74d7");
        $instance->setSecurityCode("123");
        $instance->setBrand("visa");
        $instance->setAlias("PrimeiroTokenASerGerado");

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
