<?php

namespace Braspag\Gateway\Exception;

use Throwable;
use Curl\Curl;

class BraspagRequest extends \UnexpectedValueException
{
    private $request;
    private $requestBody;
    private $errors = [];

    public function __construct(
        Curl $curl,
        $requestBody,
        string $message = null,
        int $code = 0,
        Throwable $previous = null
    ) {
        parent::__construct($message, $code, $previous);

        $this->request = $curl;
        $this->requestBody = $requestBody;
    }

    /**
    * @return Curl
    */
    public function getRequest(): Curl
    {
        return $this->request;
    }

    /**
    * @return mixed Retorna a resposta do servidor
    */
    public function getResponse()
    {
        return $this->request->getResponse();
    }

    /**
    * @return int Retorna o Status Code da resposta
    */
    public function getHttpStatus(): int
    {
        return $this->request->getHttpstatus();
    }

    /**
    * Define o corpo da mensagem enviada
    *
    * @param mixed $value
    *
    * @return self
    */
    public function setRequestBody($value)
    {
        $this->requestBody = $value;

        return $this;
    }

    /**
    * @return mixed
    */
    public function getRequestBody()
    {
        return $this->requestBody;
    }

    /**
    * @return \Error[]
    */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
