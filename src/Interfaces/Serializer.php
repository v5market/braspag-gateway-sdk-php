<?php

namespace Braspag\Gateway\Interfaces;

interface Serializer extends \JsonSerializable
{
    /**
     * Método para popular os dados na classe
     *
     * @param \stdClass $value
     *
     * @return self
     */
    public function populate(\stdClass $value);

    /**
     * Converte os dados da classe para array
     *
     * @return array
     */
    public function toArray(): array;
}
