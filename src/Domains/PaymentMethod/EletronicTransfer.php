<?php

namespace Braspag\Gateway\Domains\PaymentMethod;

use Braspag\Gateway\Interfaces\Serializer;

class EletronicTransfer implements Serializer
{
    /** @var array Payment.Beneficiary.* */
    private $beneficiary = [];

    /** @var array Payment.Shopper.* */
    private $shopper = [];


    /** @var string Payment.Url */
    private $url;

    /** @var int Payment.Status */
    private $status;

    /**
     * Banco do pagador
     *
     * @param string $value
     *
     * @return self
     */
    public function setBeneficiaryBank(string $value): self
    {
        $this->beneficiary['Bank'] = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getBeneficiaryBank(): ?string
    {
        return $this->beneficiary['Bank'] ?? null;
    }

    /**
     * Agência do pagador
     *
     * @param string $value
     */
    public function setShopperBranch(string $value): self
    {
        $this->shopper['Branch'] = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getShopperBranch(): ?string
    {
        return $this->shopper['Branch'] ?? null;
    }

    /**
     * Define conta do pagador
     *
     * @param string $value
     *
     * @return self
     */
    public function setShopperAccount(string $value): self
    {
        $this->shopper['Account'] = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getShopperAccount(): ?string
    {
        return $this->shopper['Account'] ?? null;
    }

    /**
     * @return string|null URL para a qual o comprador deverá ser redirecionado para autenticação
     */
    public function getUrl(): ?string
    {
        return $this->url;
    }

    /**
     * @return int|null Status da Transação
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        if (isset($value->Beneficiary) && isset($value->Beneficiary->Bank)) {
            $this->setBeneficiaryBank($value->Beneficiary->Bank);
        }

        if (isset($value->Shopper)) {
            if (isset($value->Shopper->Branch)) {
                $this->setShopperBranch($value->Shopper->Branch);
            }

            if (isset($value->Shopper->Account)) {
                $this->setShopperAccount($value->Shopper->Account);
            }
        }

        if (isset($value->Url)) {
            $this->url = $value->Url;
        }

        if (isset($value->Status)) {
            $this->status = $value->Status;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $arr = array_combine($keys, array_values($arr));

        return array_filter($arr, function ($item) {
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
