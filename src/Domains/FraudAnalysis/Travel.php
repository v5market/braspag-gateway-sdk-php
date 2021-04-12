<?php

namespace Braspag\Gateway\Domains\FraudAnalysis;

use Braspag\Gateway\Interfaces\Serializer;
use Braspag\Gateway\Constants\FraudAnalysis\Travel as Constants;
use DateTime;

class Travel implements Serializer
{
    /** @var string Payment.FraudAnalysis.Travel.JourneyType */
    private $journeyType;

    /** @var DateTime Payment.FraudAnalysis.Travel.DepartureTime */
    private $departureTime;

    /** @var Passenger[] Payment.FraudAnalysis.Travel.Passengers[n] */
    private $passengers = [];

    /**
     * Define o tipo de viagem
     *
     * @param string $value
     *
     * @throws \InvalidArgumentException Quando o tipo for inválido
     *
     * @return self
     */
    public function setJourneyType(string $value): self
    {
        if (!Constants::has($value) && !Constants::isValidValue($value)) {
            throw new \InvalidArgumentException('Journey Type invalid', 5000);
        }

        $this->journeyType = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getJourneyType(): string
    {
        return $this->journeyType;
    }

    /**
     * Define a data e hora da partida
     *
     * @param DateTime $value
     *
     * @return self
     */
    public function setDepartureTime(DateTime $value): self
    {
        $this->departureTime = $value;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDepartureTime(): DateTime
    {
        return $this->departureTime;
    }

    /**
     * Define os passageiros
     *
     * @param Passenger[] $values
     *
     * @return self
     */
    public function setPassengers(array $values = []): self
    {
        $this->passengers = [];

        foreach ($values as $value) {
            $this->addPassenger($value);
        }

        return $this;
    }

    /**
     * Adiciona mais um passageiro
     *
     * @param Passenger $value
     *
     * @return self
     */
    public function addPassenger(Passenger $value): self
    {
        $this->passengers[] = $value;
        return $this;
    }

    /**
     * @return Passenger[]
     */
    public function getPassengers(): array
    {
        return $this->passengers;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value): self
    {
        $this->journeyType = $value->JourneyType ?? null;

        if (isset($value->DepartureTime)) {
            $this->departureTime = DateTime::createFromFormat('Y-m-d H:i', $value->DepartureTime);
        }

        if (isset($value->Passengers)) {
            foreach ($value->Passengers as $passengerData) {
                $passenger = new Passenger();
                $passenger->populate($passengerData);
                $this->addPassenger($passenger);
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $journeyType = $this->journeyType;

        if ($this->departureTime instanceof DateTime) {
            $departureTime = $this->departureTime->format('Y-m-d H:i');
        } else {
            $separtureTime = null;
        }

        $passengers = array_map(function ($item) {
            return call_user_func([$item, 'toArray'], $item);
        }, $this->passengers);

        return array_filter([
            "JourneyType" => $journeyType,
            "DepartureTime" => $departureTime,
            "Passengers" => $passengers
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
