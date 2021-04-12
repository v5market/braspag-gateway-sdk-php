<?php

namespace Braspag\Gateway\Domains;

use Braspag\Gateway\Interfaces\Serializer;

/**
 * Somente leitura
 */
class Merchant
{
    /** @var string Merchant.Id */
    private $id;

    /** @var string Merchant.TradeName */
    private $tradeName;

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getTradeName(): ?string
    {
        return $this->tradeName;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->id = $value->Id ?? null;
        $this->tradeName = $value->TradeName ?? null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter([
            "Id" => $this->id,
            "TradeName" => $this->tradeName
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
