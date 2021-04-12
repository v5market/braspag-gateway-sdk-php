<?php

namespace Braspag\Gateway\Domains\PaymentMethod;

use Braspag\Gateway\Interfaces\Serializer;

abstract class AbstractCard implements Serializer
{
    protected $cardNumber;
    protected $holder;
    protected $expirationDate;
    protected $securityCode;
    protected $brand;
    protected $saveCard;

    /**
     * Número do Cartão do comprador
     *
     * @param string $value
     *
     * @return self
     */
    public function setNumber(string $value): self
    {
        $this->cardNumber = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->cardNumber;
    }

    /**
     * Nome do portador impresso no cartão
     *
     * @param string $value
     *
     * @return self
     */
    public function setHolder(string $value): self
    {
        $this->holder = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getHolder(): string
    {
        return $this->holder;
    }

    /**
     * Data de validade impresso no cartão no formato MM/AAAA
     *
     * @param string $value
     *
     * @return self
     */
    public function setExpirationDate(string $value): self
    {
        if (!preg_match('/^(?:0[1-9]|1[0-2])\/\d{4}$/', $value)) {
            throw new \InvalidArgumentException('Expiration date must be in MM/YYYY format', 5500);
        }

        $this->expirationDate = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getExpirationDate(): string
    {
        return $this->expirationDate;
    }

    /**
     * Código de segurança impresso no verso do cartão
     *
     * @param string $value
     *
     * @return self
     */
    public function setSecurityCode(string $value): self
    {
        $this->securityCode = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSecurityCode(): string
    {
        return $this->securityCode;
    }

    /**
     * Bandeira do cartão
     *
     * @param string $value
     *
     * @return void
     */
    public function setBrand(string $value)
    {
        $this->brand = strtolower($value);
    }

    /**
     * @return string
     */
    public function getBrand(): string
    {
        return $this->brand;
    }

    /**
     * Identifica se o cartão será salvo para gerar o token (CardToken)
     *
     * @param bool $value
     *
     * @return self
     */
    public function setSaveCard(bool $value): self
    {
        $this->saveCard = $value;
        return $this;
    }

    /**
     * @return bool
     */
    public function getSaveCard(): bool
    {
        return $this->saveCard;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->cardNumber = $value->CardNumber ?? null;
        $this->holder = $value->Holder ?? null;
        $this->expirationDate = $value->ExpirationDate ?? null;
        $this->securityCode = $value->SecurityCode ?? null;
        $this->brand = $value->Brand ?? null;
        $this->saveCard = $value->SaveCard ?? null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);
        $arr = array_combine($keys, $values);

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

    /**
     * @return boolean Retorna se é um cartão de crédito
     */
    abstract public function isCreditCard(): bool;
}
