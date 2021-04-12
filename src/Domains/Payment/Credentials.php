<?php

namespace Braspag\Gateway\Domains\Payment;

use Braspag\Gateway\Interfaces\Serializer;

class Credentials implements Serializer
{
    /** @var string Payment.Credentials.Code */
    private $code;

    /** @var string Payment.Credentials.Key */
    private $key;

    /** @var string Payment.Credentials.Username */
    private $username;

    /** @var string Payment.Credentials.Password */
    private $password;

    /** @var string Payment.Credentials.Signature */
    private $signature;

    /**
     * @param string|null $code
     * @param string|null $key
     * @param string|null $username
     * @param string|null $password
     * @param string|null $signature
     */
    public function __construct(
        ?string $code = null,
        ?string $key = null,
        ?string $username = null,
        ?string $password = null,
        ?string $signature = null
    ) {
        if (!empty($code)) {
            $this->setCode($code);
        }

        if (!empty($key)) {
            $this->setKey($key);
        }

        if (!empty($username)) {
            $this->setUsername($username);
        }

        if (!empty($password)) {
            $this->setPassword($password);
        }

        if (!empty($signature)) {
            $this->setSignature($signature);
        }
    }

    /**
     * Define código de afiliação gerada pela adquirente
     *
     * @param string $value
     *
     * @return self
     */
    public function setCode(string $value): self
    {
        $this->code = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCode(): string
    {
        return $this->code;
    }

    /**
     * Define a chave de afiliação/token gerado pela adquirente
     *
     * @param string $value
     *
     * @return self
     */
    public function setKey(string $value): self
    {
        $this->key = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * Define o usuário gerado no credenciamento com a adquirente Getnet
     *
     * @param string $value
     *
     * @return self
     */
    public function setUsername(string $value): self
    {
        $this->username = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }

    /**
     * Define a senha gerada no credenciamento com a adquirente Getnet
     *
     * @param string $value
     *
     * @return self
     */
    public function setPassword(string $value): self
    {
        $this->password = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * Define a ID do terminal no credenciamento com a adquirente Global Payments
     *
     * @param string $value
     *
     * @return self
     */
    public function setSignature(string $value): self
    {
        $this->signature = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getSignature(): string
    {
        return $this->signature;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->code = $value->Code ?? $value->code ?? null;
        $this->key = $value->Key ?? $value->key ?? null;
        $this->username = $value->Username ?? $value->username ?? null;
        $this->password = $value->Password ?? $value->password ?? null;
        $this->signature = $value->Signature ?? $value->signature ?? null;

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
