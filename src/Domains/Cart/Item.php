<?php

namespace Braspag\Gateway\Domains\Cart;

use Braspag\Gateway\Interfaces\Serializer;
use Braspag\Gateway\Constants\Cart\Item as Constants;

class Item implements Serializer
{
    private $giftCategory;
    private $hostHedge = Constants::VALUE_NORMAL;
    private $nonSensicalHedge = Constants::VALUE_NORMAL;
    private $obscenitiesHedge = Constants::VALUE_NORMAL;
    private $phoneHedge = Constants::VALUE_NORMAL;
    private $name;
    private $quantity;
    private $sku;
    private $unitPrice;
    private $risk;
    private $timeHedge = Constants::VALUE_NORMAL;
    private $type = 'Default';
    private $velocityHedge = Constants::VALUE_NORMAL;

    /**
     * Identifica que avaliará os endereços de cobrança e entrega para diferentes cidades, estados ou países
     *
     * @param string $value
     *
     * @return self
     */
    public function setGiftCategory(string $value): self
    {
        $this->giftCategory = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getGiftCategory(): string
    {
        return $this->giftCategory;
    }

    /**
     * Nível de importância dos endereços de IP e e-mail do comprador na análise de fraude
     *
     * @param string $value
     *
     * @return self
     */
    public function setHostHedge(string $value): self
    {
        $this->hostHedge = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getHostHedge(): string
    {
        return $this->hostHedge;
    }

    /**
     * Nível de importância das verificações sobre os dados do comprador sem
     * sentido na análise de fraude
     *
     * @param string $value
     *
     * @return self
     */
    public function setNonSensicalHedge(string $value): self
    {
        $this->nonSensicalHedge = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getNonSensicalHedge(): string
    {
        return $this->nonSensicalHedge;
    }

    /**
     * Nível de importância das verificações sobre os dados do comprador com
     * obscenidade na análise de fraude
     *
     * @param string $value
     *
     * @return self
     */
    public function setObscenitiesHedge(string $value): self
    {
        $this->obscenitiesHedge = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getObscenitiesHedge(): string
    {
        return $this->obscenitiesHedge;
    }

    /**
     * Nível de importância das verificações sobre os números de telefones do
     * comprador na análise de fraude
     *
     * @param string $value
     *
     * @return self
     */
    public function setPhoneHedge(string $value): self
    {
        $this->phoneHedge = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneHedge(): string
    {
        return $this->phoneHedge;
    }

    /**
     * Nome do Produto
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
     * Quantidade do produto
     *
     * @param int $value
     *
     * @return self
     */
    public function setQuantity(int $value): self
    {
        $this->quantity = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getQuantity(): int
    {
        return $this->quantity;
    }

    /**
     * SKU (Stock Keeping Unit - Unidade de Controle de Estoque) do produto
     *
     * @param string $value
     *
     * @return self
     */
    public function setSku(string $value): self
    {
        $this->sku = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getSku(): string
    {
        return $this->sku;
    }

    /**
     * Preço unitário do produto
     *
     * @param int $value
     *
     * @return self
     */
    public function setUnitPrice(int $value): self
    {
        $this->unitPrice = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getUnitPrice(): int
    {
        return $this->unitPrice;
    }

    /**
     * Nível de risco do produto associado a quantidade de chargebacks
     *
     * @param string $value
     *
     * @return self
     */
    public function setRisk(string $value): self
    {
        $this->risk = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getRisk(): string
    {
        return $this->risk;
    }

    /**
     * Nível de importância da hora do dia na análise de fraude que o comprador
     * realizou o pedido
     *
     * @param string $value
     *
     * @return self
     */
    public function setTimeHedge(string $value): self
    {
        $this->timeHedge = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getTimeHedge(): string
    {
        return $this->timeHedge;
    }

    /**
     * Categoria do produto
     *
     * @param string $value
     *
     * @return self
     */
    public function setType(string $value): self
    {
        $this->type = strtolower($value);

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
     * Nível de importância da frequência de compra do comprador na análise de fraude dentros dos 15 minutos anteriores
     *
     * @param string $value
     */
    public function setVelocityHedge(string $value): self
    {
        $this->velocityHedge = strtolower($value);

        return $this;
    }

    /**
     * @return string
     */
    public function getVelocityHedge(): string
    {
        return $this->velocityHedge;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->giftCategory = $value->GiftCategory ?? null;
        $this->hostHedge = $value->HostHedge ?? null;
        $this->nonSensicalHedge = $value->NonSensicalHedge ?? null;
        $this->obscenitiesHedge = $value->ObscenitiesHedge ?? null;
        $this->phoneHedge = $value->PhoneHedge ?? null;
        $this->name = $value->Name ?? null;
        $this->quantity = $value->Quantity ?? null;
        $this->sku = $value->Sku ?? null;
        $this->unitPrice = $value->UnitPrice ?? null;
        $this->risk = $value->Risk ?? null;
        $this->timeHedge = $value->TimeHedge ?? null;
        $this->type = $value->Type ?? null;
        $this->velocityHedge = $value->VelocityHedge ?? null;

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
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
