<?php

namespace Braspag\Gateway\Domains\Payment;

use DateTime;
use Braspag\Gateway\Interfaces\Serializer;

/**
 * Somente leitura
 */
class Chargeback implements Serializer
{
    /** @var int Payment.Chargebacks[n].Amount */
    private $amount;

    /** @var string Payment.Chargebacks[n].CaseNumber */
    private $caseNumber;

    /** @var DateTime Payment.Chargebacks[n].Date */
    private $date;

    /** @var string Payment.Chargebacks[n].ReasonCode */
    private $reasonCode;

    /** @var string Payment.Chargebacks[n].ReasonMessage */
    private $reasonMessage;

    /** @var string Payment.Chargebacks[n].Status */
    private $status;

    /** @var string Payment.Chargebacks[n].RawData */
    private $rawData;

    /**
     * @return int
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getCaseNumber(): ?string
    {
        return $this->caseNumber;
    }

    /**
     * @return DateTime
     */
    public function getDate(): ?DateTime
    {
        return $this->date;
    }

    /**
     * @return string
     */
    public function getReasonCode(): ?string
    {
        return $this->reasonCode;
    }

    /**
     * @return string
     */
    public function getReasonMessage(): ?string
    {
        return $this->reasonMessage;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @return mixed
     */
    public function getRawData()
    {
        return $this->rawData;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->amount = $value->Amount ?? null;
        $this->caseNumber = $value->CaseNumber ?? null;
        $this->date = $value->Date ?? null;
        $this->reasonCode = $value->ReasonCode ?? null;
        $this->reasonMessage = $value->ReasonMessage ?? null;
        $this->status = $value->Status ?? null;
        $this->rawData = $value->RawData ?? null;

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
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
