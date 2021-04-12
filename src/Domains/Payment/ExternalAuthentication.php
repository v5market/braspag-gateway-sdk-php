<?php

namespace Braspag\Gateway\Domains\Payment;

use Braspag\Gateway\Interfaces\Serializer;

class ExternalAuthentication implements Serializer
{
    /** @var string Payment.ExternalAuthentication.ReturnUrl */
    private $returnUrl;

    /** @var string Payment.ExternalAuthentication.Cavv */
    private $cavv;

    /** @var string Payment.ExternalAuthentication.Cavv */
    private $xid;

    /** @var int Payment.ExternalAuthentication.Cavv */
    private $eci;

    /** @var int Payment.ExternalAuthentication.Version */
    private $version;

    /** @var string Payment.ExternalAuthentication.ReferenceID */
    private $referenceId;

    /**
     * @param string $value
     *
     * @return self
     */
    public function setReturnUrl(string $value)
    {
        $this->returnUrl = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setCavv(string $value)
    {
        $this->cavv = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getCavv(): string
    {
        return $this->cavv;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setXid(string $value)
    {
        $this->xid = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getXid(): string
    {
        return $this->xid;
    }

    /**
     * @param int $value
     *
     * @return self
     */
    public function setEci(int $value)
    {
        $this->eci = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getEci(): int
    {
        return $this->eci;
    }

    /**
     * @param int $value
     *
     * @return self
     */
    public function setVersion(int $value)
    {
        $this->version = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getVersion(): int
    {
        return $this->version;
    }

    /**
     * @param string $value
     *
     * @return self
     */
    public function setReferenceId(string $value)
    {
        $this->referenceId = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getReferenceId(): string
    {
        return $this->referenceId;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->returnUrl = $value->ReturnUrl ?? null;
        $this->cavv = $value->Cavv ?? null;
        $this->xid = $value->Xid ?? null;
        $this->eci = $value->Eci ?? null;
        $this->version = $value->Version ?? null;
        $this->referenceId = $value->ReferenceId ?? null;

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
