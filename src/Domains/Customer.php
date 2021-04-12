<?php

namespace Braspag\Gateway\Domains;

use Braspag\Gateway\Interfaces\Serializer;
use Respect\Validation\Validator as v;

class Customer implements Serializer
{
    private $name;
    private $document;
    private $email;
    private $birthdate;
    private $address;
    private $deliveryAddress;
    private $phone;

    /**
     * Define o nome do cliente
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
     * Define o documento do cliente CPF ou CNPJ
     *
     * @param Document $value
     *
     * @return self
     */
    public function setDocument(Document $value): self
    {
        $this->document = $value;
        return $this;
    }

    /**
     * @return Document
     */
    public function getDocument(): Document
    {
        return $this->document;
    }

    /**
     * Define o endereço de e-mail do cliente
     *
     * @param string $value
     *
     * @return self
     */
    public function setEmail(string $value): self
    {
        if (!v::email()->validate($value)) {
            throw new \InvalidArgumentException("E-mail $value is invalid", 1500);
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
     * Define a data de nascimento
     *
     * @param \DateTime $value
     */
    public function setBirthdate(\DateTime $value): self
    {
        $this->birthdate = $value->format('Y-m-d');
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getBirthdate(): \DateTime
    {
        return \DateTime::createFromFormat('Y-m-d', $this->birthdate);
    }

    /**
     * Define o endereço de pagamento
     *
     * @param Address $value
     */
    public function setAddress(Address $value): self
    {
        $this->address = $value;
        return $this;
    }

    /**
     * @return Address
     */
    public function getAddress(): Address
    {
        return $this->address;
    }

    /**
     * Define o endereço de entrega
     *
     * @param Address $value
     */
    public function setDeliveryAddress(Address $value): self
    {
        $this->deliveryAddress = $value;
        return $this;
    }

    /**
     * @return Address
     */
    public function getDeliveryAddress(): Address
    {
        return $this->deliveryAddress;
    }

    public function setPhone(string $value, string $ddi = '55'): self
    {
        $this->phone = "{$ddi}{$value}";

        return $this;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value): self
    {
        $this->name = $value->Name ?? null;
        $this->email = $value->Email ?? null;
        $this->birthdate = $value->Birthdate ?? null;
        $this->phone = $value->Phone ?? null;

        if (isset($value->Identity)) {
            if (strlen(preg_replace('/\D/', '', $value->Identity)) === 11) {
                $this->document = Document::cpf($value->Identity);
            } elseif (strlen(preg_replace('/\D/', '', $value->Identity)) === 14) {
                $this->document = Document::cnpj($value->Identity);
            }
        }

        if (isset($value->Address)) {
            $this->address = (new Address())->populate($value->Address);
        }

        if (isset($value->DeliveryAddress)) {
            $this->deliveryAddress = (new Address())->populate($value->DeliveryAddress);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter([
            "Name" => $this->name,
            "Identity" => ($this->document) ? $this->document->getValue() : null,
            "IdentityType" => ($this->document) ? $this->document->getType() : null,
            "Email" => $this->email,
            "Birthdate" => $this->birthdate,
            "Phone" => $this->phone,
            "Address" => ($this->address) ? $this->address->toArray() : null,
            "DeliveryAddress" => ($this->deliveryAddress) ? $this->deliveryAddress->toArray() : null,
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
