<?php

namespace Braspag\Gateway\Domains\Payment;

use DateTime;
use Braspag\Gateway\Constants\Payment\Refund as Constants;
use Braspag\Gateway\Interfaces\Serializer;

/**
 * Somente leitura
 */
class Refund implements Serializer
{
    /** @var int Payment.Refunds.Amount */
    private $amount;

    /** @var int Payment.Refunds.Status */
    private $status = null;

    /** @var DateTime Payment.Refunds.ReceivedDate */
    private $receivedDate;

    /**
     * @return int|null Valor Reembolsado
     */
    public function getAmount(): ?int
    {
        return $this->amount;
    }

    /**
     * @return int|null Status do Reembolso
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return string|null Nome do status de reembolso
     */
    public function getStatusName(): ?string
    {
        return Constants::STATUS[$this->status] ?? null;
    }

    /**
     * @return DateTime|null
     */
    public function getReceivedDate(): ?DateTime
    {
        return $this->receivedDate;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->amount = $value->Amount ?? null;
        $this->status = $value->Status ?? null;

        if (isset($value->ReceivedDate)) {
            $this->receivedDate = DateTime::createFromFormat('Y-m-d H:i:s', $value->ReceivedDate);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter([
            "Amount" => $this->amount,
            "Status" => $this->status,
            "ReceivedDate" => $this->receivedDate->format('Y-m-d H:i:s')
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
