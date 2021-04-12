<?php

namespace Braspag\Gateway\Domains;

use Braspag\Gateway\Interfaces\Serializer;

class Sale implements Serializer
{
    /** @var string MerchantOrderId */
    private $merchantOrderId;

    /** @var Customer Customer.* */
    private $customer;

    /** @var Merchant Merchant.* */
    private $merchant;

    /** @var Payment Payment.* */
    private $payment;

    /** @var string */
    private $responseRaw;

    /**
     * Define o Número do pedido da loja
     *
     * @param string $value
     *
     * @return self
     */
    public function setMerchantOrderId(string $value): self
    {
        $this->merchantOrderId = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getMerchantOrderId(): string
    {
        return $this->merchantOrderId;
    }

    /**
     * Informa os dados do cliente
     *
     * @param Customer $value
     *
     * @return self
     */
    public function setCustomer(Customer $value): self
    {
        $this->customer = $value;

        return $this;
    }

    /**
     * @return Customer
     */
    public function getCustomer(): Customer
    {
        return $this->customer;
    }

    /**
     * Informa os dados de pagamento
     *
     * @param Payment $value
     *
     * @return self
     */
    public function setPayment(Payment $value): self
    {
        $this->payment = $value;

        return $this;
    }

    /**
     * @return Merchant
     */
    public function getmerchant(): Merchant
    {
        return $this->merchant;
    }

    /**
     * @return Payment
     */
    public function getPayment(): Payment
    {
        return $this->payment;
    }

    /**
     * @return string
     */
    public function getResponseRaw(): string
    {
        return $this->responseRaw;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->responseRaw = json_encode($value);
        $this->merchantOrderId = $value->MerchantOrderId ?? null;

        if (isset($value->Customer)) {
            $customer = new Customer();
            $customer->populate($value->Customer);
            $this->customer = $customer;
        }

        if (isset($value->Merchant)) {
            $merchant = new Merchant();
            $merchant->populate($value->Merchant);
            $this->merchant = $merchant;
        }

        if (isset($value->Payment)) {
            $payment = new Payment();
            $payment->populate($value->Payment);
            $this->payment = $payment;
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        return array_filter([
            "MerchantOrderId" => $this->merchantOrderId,
            "Customer" => $this->customer ? $this->customer->toArray() : null,
            "Merchant" => $this->merchant ? $this->merchant->toArray() : null,
            "Payment" => $this->payment ? $this->payment->toArray() : null
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
