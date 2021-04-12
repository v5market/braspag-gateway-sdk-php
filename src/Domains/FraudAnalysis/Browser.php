<?php

namespace Braspag\Gateway\Domains\FraudAnalysis;

use Braspag\Gateway\Interfaces\Serializer;
use Respect\Validation\Validator as v;

class Browser implements Serializer
{
    /** @var string Payment.FraudAnalysis.Browser.HostName */
    private $hostName;

    /** @var bool Payment.FraudAnalysis.Browser.CookiesAccepted */
    private $cookiesAccepted;

    /** @var string Payment.FraudAnalysis.Browser.Email */
    private $email;

    /** @var string Payment.FraudAnalysis.Browser.Type */
    private $type;

    /** @var string Payment.FraudAnalysis.Browser.IpAddress */
    private $ipAddress;

    public function __construct(
        string $ipAddress = null,
        string $email = null,
        string $type = null,
        string $hostName = null,
        bool $cookiesAccepted = null
    ) {
        if ($ipAddress) {
            $this->setIpAddress($ipAddress);
        }

        if ($email) {
            $this->setEmail($email);
        }

        if ($type) {
            $this->setType($type);
        }

        if ($hostName) {
            $this->setHostName($hostName);
        }

        if ($cookiesAccepted !== null) {
            $this->setCookiesAccepted($cookiesAccepted);
        }
    }

    /**
     * Nome do host informado pelo browser do comprador e identificado através do cabeçalho HTTP
     *
     * @param string $value
     */
    public function setHostname(string $value): self
    {
        $this->hostName = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getHostname(): string
    {
        return $this->hostName;
    }

    /**
     * Identifica se o browser do comprador aceita cookies
     *
     * @param bool $value
     *
     * @return self
     */
    public function setCookiesAccepted(bool $value): self
    {
        $this->cookiesAccepted = $value;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getCookiesAccepted(): bool
    {
        return $this->cookiesAccepted;
    }

    /**
     * E-mail registrado no browser do comprador.
     *
     * @param string $value
     *
     * @return self
     */
    public function setEmail(string $value): self
    {
        if (!v::email()->validate($value)) {
            throw new \InvalidArgumentException('Email is invalid', 4000);
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
     * Nome do browser utilizado pelo comprador e identificado através do cabeçalho HTTP
     *
     * @param string $value
     *
     * @return self
     */
    public function setType(string $value): self
    {
        $this->type = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Endereço de IP do comprador. Formato IPv4 ou IPv6
     *
     * @param string $value
     *
     * @return self
     */
    public function setIpAddress(string $value): self
    {
        if (!v::ip()->validate($value)) {
            throw new \InvalidArgumentException('Ip address is invalid', 4001);
        }

        $this->ipAddress = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getIpAddress(): string
    {
        return $this->ipAddress;
    }

    /**
     * {@inheritDoc}
    */
    public function toArray(): array
    {
        $arr = get_object_vars($this);
        $keys = array_map('ucfirst', array_keys($arr));
        $values = array_values($arr);

        $newArr = array_combine($keys, $values);

        return array_filter($newArr, function ($item) {
            return $item !== null;
        });
    }

    /**
     * {@inheritDoc}
    */
    public function populate(\stdClass $value)
    {
        $this->hostName = $value->HostName ?? null;
        $this->cookiesAccepted = $value->CookiesAccepted ?? null;
        $this->email = $value->Email ?? null;
        $this->type = $value->Type ?? null;
        $this->ipAddress = $value->IpAddress ?? null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
