<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\FraudAnalysis\Passenger;
use Braspag\Gateway\Domains\Document;

use Braspag\Gateway\Constants\FraudAnalysis\Passenger as Constants;

class PassengerTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Passenger();
        $this->assertInstanceOf(Passenger::class, $instance);
    }

    /**
     * @test
     */
    public function definingADocumentMustReturnAString()
    {
        $doc = Document::cnpj('00000000000191');

        $instance = new Passenger();
        $instance->setIdentity($doc);
        $this->assertEquals('00000000000191', $instance->getIdentity());

        $instance->setIdentity('31195855');
        $this->assertEquals('31195855', $instance->getIdentity());
    }

    /**
     * @test
     */
    public function setAnValidStatusWithoutError()
    {
        $instance = new Passenger();
        $instance->setStatus(Constants::STATUS_GOLD);
        $this->assertEquals(Constants::STATUS_GOLD, $instance->getStatus());
    }

    /**
     * @test
     */
    public function setAnValidRatingWithoutError()
    {
        $instance = new Passenger();
        $instance->setRating(Constants::RATING_ADULT);
        $this->assertEquals(Constants::RATING_ADULT, $instance->getRating());
    }

    /**
     * @test
     */
    public function checkEmailInvalidShouldGiveError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Passenger();
        $instance->setEmail(".@test.com");
    }

    /**
     * @test
     */
    public function setEmailValidCanNotShouldGiveError()
    {
        $instance = new Passenger();
        $instance->setEmail('example@test.com');
        $this->assertEquals('example@test.com', $instance->getEmail());
    }

    /**
     * @test
     */
    public function checksWhetherTheConcatenationOccursWithoutErrors()
    {
        $instance = new Passenger();
        $instance->setPhone('71 9 1234-5678');
        $this->assertEquals('5571912345678', $instance->getPhone());

        $instance->setPhone('71912345678', '+1');
        $this->assertEquals('171912345678', $instance->getPhone());
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "Name":"Passenger Test",
            "Identity":"212424808",
            "Status":"Gold",
            "Rating":"Adult",
            "Email":"email@mail.com",
            "Phone":"5564991681074",
            "TravelLegs":[
               {
                  "Origin":"AMS",
                  "Destination":"GIG"
               }
            ]
        }';

        $instance = new Passenger();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
