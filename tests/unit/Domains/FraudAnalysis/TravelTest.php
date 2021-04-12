<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\FraudAnalysis\Travel;
use Braspag\Gateway\Domains\FraudAnalysis\Passenger;
use Braspag\Gateway\Constants\FraudAnalysis\Travel as Constants;

class TravelTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Travel();
        $this->assertInstanceOf(Travel::class, $instance);
    }

    /**
     * @test
     */
    public function definesAnInvalidJourneyTypeShouldReturnError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Travel();
        $instance->setJourneyType('Invalid');
    }

    /**
     * @test
     */
    public function definesAnValidJourneyTypeCanNotShouldReturnError()
    {
        $instance = new Travel();
        $instance->setJourneyType(Constants::TYPE_ONE_WAY_TRIP);
        $this->assertEquals(Constants::TYPE_ONE_WAY_TRIP, $instance->getJourneyType());
    }

    /**
     * @test
     */
    public function checkThatTheNumberOfPassengersIsCorrect()
    {
        $instance = new Travel();
        $instance->addPassenger(new Passenger());
        $instance->setPassengers([
            new Passenger()
        ]);
        $instance->addPassenger(new Passenger());

        $this->assertCount(2, $instance->getPassengers());
        $this->assertContainsOnlyInstancesOf(Passenger::class, $instance->getPassengers());
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "JourneyType":"OneWayTrip",
            "DepartureTime":"2018-01-09 18:00",
            "Passengers":[
               {
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
               }
            ]
        }';

        $instance = new Travel();
        $instance->populate(json_decode($json));

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
