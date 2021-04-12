<?php

namespace Braspag\Gateway\Domains\Payment;

use DateTime;
use Braspag\Gateway\Interfaces\Serializer;

class FraudAlert implements Serializer
{
    /** @var DateTime Payment.FraudAlert.Date */
    private $date;

    /** @var string Payment.FraudAlert.ReasonMessage */
    private $reasonMessage;

    /** @var mixed Payment.FraudAlert.IncomingChargeback */
    private $incomingChargeback;

    /**
     * @return DateTime|null
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @return string|null
     */
    public function getReasonMessage(): ?string
    {
        return $this->reasonMessage;
    }

    /**
     * @return mixed
     */
    public function getIncomingChargeback()
    {
        return $this->incomingChargeback;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->date = DateTime::createFromFormat('Y-m-d', $value->Date);
        $this->reasonMessage = $value->ReasonMessage;
        $this->incomingChargeback = $value->IncomingChargeback;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter([
            "Date" => $this->date->format('Y-m-d'),
            "ReasonMessage" => $this->reasonMessage,
            "IncomingChargeback" => $this->incomingChargeback
        ], function ($item) {
            return $item !== null;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
