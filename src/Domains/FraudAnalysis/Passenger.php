<?php

namespace Braspag\Gateway\Domains\FraudAnalysis;

use Braspag\Gateway\Domains\Document;
use Braspag\Gateway\Interfaces\Serializer;
use Braspag\Gateway\Constants\FraudAnalysis\Passenger as Constants;
use Respect\Validation\Validator as v;

class Passenger implements Serializer
{
    /** @var string Payment.FraudAnalysis.Travel.Passengers[n].Name */
    private $name;

    /** @var string Payment.FraudAnalysis.Travel.Passengers[n].Identity */
    private $identity;

    /** @var string Payment.FraudAnalysis.Travel.Passengers[n].Status */
    private $status;

    /** @var string Payment.FraudAnalysis.Travel.Passengers[n].Rating */
    private $rating;

    /** @var string Payment.FraudAnalysis.Travel.Passengers[n].Email */
    private $email;

    /** @var string Payment.FraudAnalysis.Travel.Passengers[n].Phone */
    private $phone;

    /** @var array Payment.FraudAnalysis.Travel.Passengers[n].TravelLegs.* */
    private $travelLegs = [];

    /**
     * Define o nome do passageiro
     *
     * @param string $value
     *
     * @return self
     */
    public function setName(string $value): self
    {
        $this->name = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * Define o número do documento
     *
     * @param Document|mixed $value
     *
     * @return self
     */
    public function setIdentity($value): self
    {
        if ($value instanceof Document) {
            $value = $value->getValue();
        }

        $this->identity = (string)$value;

        return $this;
    }

    /**
     * @return string
     */
    public function getIdentity(): string
    {
        return $this->identity;
    }

    /**
     * Define a classificação da empresa aérea
     *
     * @param string $value
     * @see Constants
     *
     * @return self
     */
    public function setStatus(string $value): self
    {
        $this->status = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getStatus(): string
    {
        return $this->status;
    }

    /**
     * Define o tipo de passageiro
     *
     * @param string $value
     * @see Constants
     *
     * @return self
     */
    public function setRating(string $value): self
    {

        $this->rating = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getRating(): string
    {
        return $this->rating;
    }

    /**
     * E-mail do passageiro
     *
     * @param string $value
     *
     * @throws \InvalidArgumentException Se o e-mail for inválido
     *
     * @return self
     */
    public function setEmail(string $value): self
    {
        if (!v::email()->validate($value)) {
            throw new \InvalidArgumentException('E-mail invalid.', 4502);
        }

        $this->email = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * Define o número de contato do passageiro
     *
     * @param string $phone Número com o DDD
     * @param string $ddi Discagem Direta Internacional
     *
     * @return self
     */
    public function setPhone(string $phone, string $ddi = '55'): self
    {
        $this->phone = preg_replace('/\D/', '', "$ddi$phone");

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * Define o código do aeroporte de orgem e destino
     *
     * @param string $origin
     * @param string $destination
     *
     * @throws \InvalidArgumentException Se o código de origem e/ou destino for inválido
     *
     * @return self
     */
    public function addTravelLegs(string $origin, string $destination): self
    {
        if (strlen($origin) !== 3) {
            throw new \InvalidArgumentException('The origin code is invalid', 4502);
        }

        if (strlen($destination) !== 3) {
            throw new \InvalidArgumentException('Destination code is invalid', 4503);
        }

        $this->travelLegs[] = [
            "Origin" => $origin,
            "Destination" => $destination
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getTravelLegs(): array
    {
        return $this->travelLegs;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->name = $value->Name ?? null;
        $this->identity = $value->Identity ?? null;
        $this->status = $value->Status ?? null;
        $this->rating = $value->Rating ?? null;
        $this->email = $value->Email ?? null;
        $this->phone = $value->Phone ?? null;

        if (isset($value->TravelLegs)) {
            foreach ($value->TravelLegs as $value) {
                $this->addTravelLegs($value->Origin, $value->Destination);
            }
        }

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
