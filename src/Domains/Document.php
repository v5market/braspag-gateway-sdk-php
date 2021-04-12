<?php

namespace Braspag\Gateway\Domains;

use Respect\Validation\Validator as v;

class Document
{
    private $identity;
    private $identityType;

    /**
     * @param string $type Cpf ou Cnpj
     * @param string $value
     */
    private function __construct(string $type, string $value)
    {
        $this->identity = $value;
        $this->identityType = $type;
    }

    /**
     * Define o CPF do comprador
     *
     * @param string $value
     *
     * @return self
     */
    public static function cpf(string $value): self
    {
        if (!v::cpf()->validate($value)) {
            throw new \InvalidArgumentException("CPF $value is invalid", 2000);
        }

        return new self('cpf', $value);
    }

    /**
     * Define o CNPJ do comprador
     *
     * @param string $value
     *
     * @return self
     */
    public static function cnpj(string $value): self
    {
        if (!v::cnpj()->validate($value)) {
            throw new \InvalidArgumentException("CNPJ $value is invalid", 2001);
        }

        return new self('cnpj', $value);
    }

    /**
     * @return string Captura o tipo do documento (Cpf ou Cnpj)
     */
    public function getType(): string
    {
        return $this->identityType;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->identity;
    }
}
