<?php

namespace Braspag\Gateway\Domains\Payment;

use Braspag\Gateway\Interfaces\Serializer;
use Braspag\Gateway\Domains\Address;
use Braspag\Gateway\Domains\Document;

/**
 * Aplicável somente para Provider=Cielo30
 */
class SubEstablishment implements Serializer
{
    /** @var string Payment.PaymentFacilitator.SubEstablishment.EstablishmentCode */
    private $establishmentCode;

    /** @var string Payment.PaymentFacilitator.SubEstablishment.Mcc */
    private $mcc;

    /** @var string Payment.PaymentFacilitator.SubEstablishment.Address */
    private $address;

    /** @var string Payment.PaymentFacilitator.SubEstablishment.City */
    private $city;

    /** @var string Payment.PaymentFacilitator.SubEstablishment.State */
    private $state;

    /** @var string Payment.PaymentFacilitator.SubEstablishment.PostalCode */
    private $postalCode;

    /** @var string Payment.PaymentFacilitator.SubEstablishment.PhoneNumber */
    private $phoneNumber;

    /** @var Document Payment.PaymentFacilitator.SubEstablishment.Identity */
    private $identity;

    /** @var string Payment.PaymentFacilitator.SubEstablishment.CountryCode */
    private $countryCode;


    /**
     * Define o código do estabelecimento do sub Merchant. “Sub-Merchant ID”
     *
     * @param string $value
     *
     * @return self
     */
    public function setEstablishmentCode(string $value): self
    {
        $this->establishmentCode = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getEstablishmentCode(): string
    {
        return $this->establishmentCode;
    }

    /**
     * Define o MCC do sub Merchant
     *
     * @param string $value
     *
     * @return self
     */
    public function setMcc(string $value): self
    {
        $this->mcc = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getMcc(): string
    {
        return $this->mcc;
    }

    /**
     * Define os campos:
     *  - Payment.PaymentFacilitator.SubEstablishment.Address
     *  - Payment.PaymentFacilitator.SubEstablishment.City
     *  - Payment.PaymentFacilitator.SubEstablishment.State
     *  - Payment.PaymentFacilitator.SubEstablishment.PostalCode
     *
     * @param string $address
     * @param string $city
     * @param string $state
     * @param string $postalCode
     *
     * @return self
     */
    public function setAddress(
        ?string $address,
        ?string $city,
        ?string $state,
        ?string $postalCode
    ): self {
        $this->address = $address;
        $this->city = $city;
        $this->state = $state;
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * Define o número de telefone do sub Merchant
     *
     * @param string $value
     */
    public function setPhoneNumber(string $value): self
    {
        $this->phoneNumber = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    /**
     * Define o CPF/CNPJ do sub Merchant
     *
     * @param Document $value
     */
    public function setDocument(Document $value): self
    {
        $this->identity = $value;
        return $this;
    }

    /**
     * @return Document|null
     */
    public function getDocument(): ?Document
    {
        return $this->identity;
    }

    /**
     * Define o CPF/CNPJ do sub Merchant
     *
     * @param string $value
     */
    public function setCountryCode(string $value): self
    {
        $this->countryCode = $value;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getCountryCode(): ?string
    {
        return $this->countryCode;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->establishmentCode = $value->EstablishmentCode ?? null;
        $this->mcc = $value->Mcc ?? null;
        $this->address = $value->Address ?? null;
        $this->city = $value->City ?? null;
        $this->state = $value->State ?? null;
        $this->postalCode = $value->PostalCode ?? null;
        $this->phoneNumber = $value->PhoneNumber ?? null;
        $this->countryCode = $value->CountryCode ?? null;

        $identity = $value->Identity ? preg_replace('/\D/', '', $value->Identity) : false;

        if ($identity && strlen($identity) == 11) {
            $this->identity = Document::cpf($identity);
        } elseif ($identity && strlen($identity) == 14) {
            $this->identity = Document::cnpj($identity);
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

        if (!empty($this->identity)) {
            $arr['identity'] = $this->identity->getValue();
        }

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
