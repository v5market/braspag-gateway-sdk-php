<?php

namespace Braspag\Gateway\Domains;

use Braspag\Gateway\Interfaces\Serializer;

class Address implements Serializer
{
    private $street;
    private $number;
    private $complement;
    private $zipCode;
    private $city;
    private $state;
    private $country;
    private $district;

    /**
     * @param string $value
     */
    public function setStreet(string $value): self
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Street is required', 1000);
        }

        $this->street = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * @param string $value
     */
    public function setNumber(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('Address number is required', 1001);
        }

        $this->number = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $value
     */
    public function setComplement(string $value): self
    {
        $this->complement = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getComplement()
    {
        return $this->complement;
    }

    /**
     * @param string $value
     */
    public function setZipcode(string $value)
    {
        $value = preg_replace('/\D/', '', $value);

        if (mb_strlen($value) != 8) {
            throw new \LengthException('The zipcode must be 8 characters', 1002);
        }

        $this->zipCode = $value;
    }

    /**
     * @return string
     */
    public function getZipcode()
    {
        return $this->zipCode;
    }

    /**
     * @param string $value
     */
    public function setCity(string $value)
    {
        if (empty($value)) {
            throw new \InvalidArgumentException('City is required', 1003);
        }

        $this->city = $value;
    }

    /**
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param string $value
     */
    public function setState(string $value)
    {
        if (mb_strlen($value) != 2) {
            throw new \LengthException('The state must be 2 characters', 1004);
        }

        $this->state = $value;
    }

    /**
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param string $value
     */
    public function setCountry(string $value)
    {
        if (mb_strlen($value) > 3) {
            throw new \LengthException('The country must be 3 characters', 1005);
        }

        $this->country = $value;
    }

    /**
     * @return string
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * @param string $value
     */
    public function setDistrict(string $value)
    {
        $this->district = $value;
    }

    /**
     * @return string
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value): self
    {
        $this->street = $value->Street ?? null;
        $this->number = $value->Number ?? null;
        $this->complement = $value->Complement ?? null;
        $this->zipCode = $value->ZipCode ?? null;
        $this->city = $value->City ?? null;
        $this->state = $value->State ?? null;
        $this->country = $value->Country ?? null;
        $this->district = $value->District ?? null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $arr = array_filter(get_object_vars($this));
        $keys = array_map('ucfirst', array_keys($arr));

        return array_combine($keys, array_values($arr));
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
