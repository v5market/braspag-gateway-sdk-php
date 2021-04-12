<?php

namespace Braspag\Gateway\Domains\Payment;

use Braspag\Gateway\Interfaces\Serializer;

class PaymentFacilitator implements Serializer
{
    /** @var int Payment.PaymentFacilitator.EstablishmentCode */
    private $establishmentCode;

    /** @var SubEstablishment Payment.PaymentFacilitator.SubEstablishment */
    private $subEstablishment;

    public function __construct(?int $code = null, ?SubEstablishment $sub = null)
    {
        if ($code !== null) {
            $this->setEstablishmentCode($code);
        }

        if ($sub) {
            $this->setSubEstablishment($sub);
        }
    }

    /**
     * Código do estabelecimento do facilitador. “Facilitator ID” (Cadastro do facilitador com as bandeiras).
     *
     * @param int $value
     *
     * @return self
     */
    public function setEstablishmentCode(int $value): self
    {
        $this->establishmentCode = $value;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getEstablishmentCode()
    {
        return $this->establishmentCode;
    }

    /**
     * Dados do estabelecimento do sub-merchant.
     *
     * @param SubEstablishment $value
     *
     * @return self
     */
    public function setSubEstablishment(SubEstablishment $value): self
    {
        $this->subEstablishment = $value;

        return $this;
    }

    /**
     * @return SubEstablishment|null
     */
    public function getSubEstablishment()
    {
        return $this->subEstablishment;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->establishmentCode = $value->EstablishmentCode ?? null;

        if ($value->SubEstablishment) {
            $subEstablishment = new SubEstablishment();
            $subEstablishment->populate($value->SubEstablishment);
            $this->subEstablishment = $subEstablishment;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter([
            'EstablishmentCode' => $this->establishmentCode,
            'SubEstablishment' => $this->subEstablishment ? $this->subEstablishment->toArray() : null,
        ]);
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
