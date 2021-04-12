<?php

namespace Braspag\Gateway\Domains\PaymentMethod;

use Braspag\Gateway\Interfaces\Serializer;

class Pix implements Serializer
{
    private $qrCodeBase64Image;
    private $qrCodeString;

    /**
     * @return string|null
     */
    public function getQrCodeBase64Image(): ?string
    {
        return $this->qrCodeBase64Image;
    }

    /**
     * @return string|null
     */
    public function getQrCodeString(): ?string
    {
        return $this->qrCodeString;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->qrCodeBase64Image = $value->QrCodeBase64Image ?? null;
        $this->qrCodeString = $value->QrCodeString ?? null;

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
