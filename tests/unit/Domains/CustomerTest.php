<?php

use Braspag\Gateway\Test\BraspagGatewayTestCase;
use Braspag\Gateway\Domains\Customer;
use Braspag\Gateway\Domains\Address;
use Braspag\Gateway\Domains\Document;

class CustomerTest extends BraspagGatewayTestCase
{
    /**
     * @test
     */
    public function newInstance()
    {
        $instance = new Customer();
        $this->assertInstanceOf(Customer::class, $instance);
    }

    /**
     * @test
     */
    public function emailInvalidShouldGiveError()
    {
        $this->expectException(InvalidArgumentException::class);
        $instance = new Customer();
        $instance->setEmail('@example.com');
    }

    /**
     * @test
     */
    public function populateData()
    {
        $json = '{
            "Name":"Nome do Comprador",
            "Identity":"72523910004",
            "IdentityType":"cpf",
            "Email":"comprador@braspag.com.br",
            "Birthdate":"1991-01-02",
            "Phone": "5521976781114",
            "Address":{
                "Street":"Alameda Xingu",
                "Number":"512",
                "Complement":"27 andar",
                "ZipCode":"12345987",
                "City":"São Paulo",
                "State":"SP",
                "Country":"BRA",
                "District":"Alphaville"
            },
            "DeliveryAddress":{
                "Street":"Alameda Xingu",
                "Number":"512",
                "Complement":"27 andar",
                "ZipCode":"12345987",
                "City":"São Paulo",
                "State":"SP",
                "Country":"BRA",
                "District":"Alphaville"
            }
        }';

        $address = new Address();
        $address->setStreet('Alameda Xingu');
        $address->setNumber('512');
        $address->setComplement('27 andar');
        $address->setZipCode('12345987');
        $address->setCity('São Paulo');
        $address->setState('SP');
        $address->setCountry('BRA');
        $address->setDistrict('Alphaville');

        $instance = new Customer();
        $instance->setName('Nome do Comprador');
        $instance->setDocument(Document::cpf('72523910004'));
        $instance->setEmail('comprador@braspag.com.br');
        $instance->setBirthdate(DateTime::createFromFormat('Y-m-d', '1991-01-02'));
        $instance->setPhone('21976781114', '55');
        $instance->setAddress($address);
        $instance->setDeliveryAddress($address);

        $this->assertJsonStringEqualsJsonString($json, json_encode($instance));
    }
}
