<?php

namespace Braspag\Gateway\Domains\Cart;

use Braspag\Gateway\Interfaces\Serializer;

class Cart implements Serializer
{
    private $isGift;
    private $returnsAccepted;
    private $items = [];

    public function __construct(...$items)
    {
        $this->setItems($items);
    }

    /**
     * Indica se o pedido realizado pelo comprador é para presente
     *
     * @param bool $value Se o valor não for booleano, converte-o
     *
     * @return self
     */
    public function setIsGift(bool $value): self
    {
        $this->isGift = $value;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getIsGift(): bool
    {
        return $this->isGift();
    }

    /**
     * @return boolean
     */
    public function isGift(): bool
    {
        return $this->isGift;
    }

    /**
     * Indica se o pedido realizado pelo comprador pode ser devolvido a loja
     *
     * @param bool $value Se o valor não for booleano, converte-o
     */
    public function setReturnsAccepted(bool $value): self
    {
        $this->returnsAccepted = $value;
        return $this;
    }

    /**
     * @return boolean
     */
    public function getReturnsAccepted(): bool
    {
        return $this->returnsAccepted;
    }

    /**
     * Adiciona um item ao carrinho
     *
     * @param Item $value
     *
     * @return self
     */
    public function addItem(Item $value): self
    {
        $this->items[] = $value;
        return $this;
    }

    /**
     * Adiciona os itens ao carrinho
     *
     * @param Item[] $values
     *
     * @return self
     */
    public function setItems($values): self
    {
        $this->items = [];

        foreach ($values as $value) {
            $this->addItem($value);
        }

        return $this;
    }

    /**
     * @return Item[]
     */
    public function getItems()
    {
        return $this->items;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->isGift = $value->IsGift ?? null;
        $this->returnsAccepted = $value->ReturnsAccepted ?? null;
        $this->items = [];

        if (isset($value->Items)) {
            foreach ($value->Items as $item) {
                $itemObj = new Item();
                $itemObj->populate($item);
                $this->items[] = $itemObj;
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $items = null;

        if ($this->items) {
            $items = array_map(function ($item) {
                return call_user_func([$item, 'toArray'], $item);
            }, $this->items);
        };

        return array_filter([
            "IsGift" => $this->isGift,
            "ReturnsAccepted" => $this->returnsAccepted,
            "Items" => $items
        ], function ($item) {
            return $item !== null;
        });
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
