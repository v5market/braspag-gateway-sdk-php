<?php

namespace Braspag\Gateway\Domains\PaymentMethod;

use Braspag\Gateway\Constants\PaymentMethod\CreditCardBrand as Brand;
use Braspag\Gateway\Constants\PaymentMethod\CardOnFile;

class CreditCard extends AbstractCard
{
    private $alias;
    private $cardOnFile = [];
    private $cardToken;

    /**
     * Nome atribuído pelo lojista ao cartão salvo como CardToken
     *
     * @param string $value
     *
     * @return self
     */
    public function setAlias(string $value): self
    {
        $this->alias = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getAlias(): string
    {
        return $this->alias;
    }

    /**
     * Aplicável somente para Provider=Cielo30
     *
     * First: se o cartão foi armazenado e é seu primeiro uso.
     * Used: se o cartão foi armazenado e ele já foi utilizado anteriormente em outra transação
     *
     * @param string $value
     *
     * @return self
     */
    public function setCardOnFileUsage(string $value): self
    {
        $value = ucfirst(strtolower($value));

        if (!CardOnFile::has($value) && !CardOnFile::isValidValue($value)) {
            throw new \InvalidArgumentException('Invalid CreditCard Usage', 6500);
        }

        $this->cardOnFile['Usage'] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardOnFileUsage(): string
    {
        return $this->cardOnFile['Usage'] ?? '';
    }

    /**
     * Aplicável somente para Provider=Cielo30
     *
     * Indica o propósito de armazenamento de cartões, caso o campo “Usage” for “Used”.
     *
     * Recurring: Compra recorrente programada (ex. assinaturas)
     * Unscheduled: Compra recorrente sem agendamento (ex. aplicativos de serviços)
     * Installments: Parcelamento através da recorrência
     *
     * @param string $value
     *
     * @return self
     */
    public function setCardOnFileReason(string $value): self
    {
        $value = ucfirst(strtolower($value));

        if (!CardOnFile::has($value) && !CardOnFile::isValidValue($value)) {
            throw new \InvalidArgumentException('Invalid CreditCard Reason', 6501);
        }

        $this->cardOnFile['Reason'] = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCardOnFileReason(): string
    {
        return $this->cardOnFile['Reason'] ?? '';
    }

    /**
     * Define o Token do cartão. Ele é gerado após gerar uma transação utilizando $this->saveCard(true)
     *
     * @param string $value
     *
     * @return self
     */
    public function setCardToken(string $value): self
    {
        $this->cardToken = $value;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCardToken(): ?string
    {
        return $this->cardToken;
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
            return $item !== null && $item !== [];
        });
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        parent::populate($value);

        $this->alias = $value->Alias ?? null;

        if (isset($value->CardOnFile)) {
            if (isset($value->CardOnFile->Usage)) {
                $this->cardOnFile['Usage'] = $value->CardOnFile->Usage;
            }

            if (isset($value->CardOnFile->Reason)) {
                $this->cardOnFile['Reason'] = $value->CardOnFile->Reason;
            }
        }

        $this->cardToken = $value->CardToken ?? null;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isCreditCard(): bool
    {
        return true;
    }
}
