<?php

namespace Braspag\Gateway\Request;

use RuntimeException;
use Braspag\Gateway\Domains\Environment;
use Braspag\Gateway\Domains\Payment;
use Braspag\Gateway\Domains\Sale as SaleObj;
use Braspag\Gateway\Exception\Auth as AuthException;
use Braspag\Gateway\Exception\NotFound as NotFoundException;
use Braspag\Gateway\Exception\BraspagRequest as BraspagRequestException;

class Sale
{
    /** @var Environment */
    private $env;

    /**
     * @param Environment $env
     */
    public function __construct(Environment $env)
    {
        $this->env = $env;
    }

    /**
     * Cria uma transação na Braspag
     *
     * @param SaleObj $value
     * @param string|null $requestId Identificador do Request definido pela loja, utilizado quando o lojista usa
     *      diferentes servidores para cada GET/POST/PUT
     *
     * @throws AuthException Caso as credentiais sejam inválidas
     * @throws NotFoundException
     * @throws RuntimeException Caso o servidor esteja fora do ar
     * @throws BraspagRequestException Caso haja algum dado inválido
     *
     * @return SaleObj
     */
    public function create(SaleObj $value, string $requestId = null)
    {
        $url = Request::createUrl($this->env);
        $request = Request::create($this->env, $requestId);
        $request->post($url, $value->toArray(), true);
        $request->close();

        $response = $request->getResponse();

        if ($request->getHttpStatus() === Request::HTTP_CODE_UNAUTHORIZED) {
            throw new AuthException('Check your credentials', 7000);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_NOT_FOUND) {
            throw new NotFoundException('Resource Not Found', 7001);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_SERVER_ERROR) {
            throw new RuntimeException('Server is down', 7002);
        } elseif ($request->isError()) {
            throw new BraspagRequestException($request, json_encode($value), 'Check your data', 7003);
        }

        $result = new SaleObj();
        $result->populate(json_decode($response));
        return $result;
    }

    /**
     * Captura as informações de um pedido
     *
     * @param string $paymentId
     * @param string|null $requestId Identificador do Request definido pela loja, utilizado quando o lojista usa
     *      diferentes servidores para cada GET/POST/PUT
     *
     * @throws AuthException Caso as credentiais sejam inválidas
     * @throws NotFoundException
     * @throws RuntimeException Caso o servidor esteja fora do ar
     *
     * @return SaleObj
     */
    public function info(string $paymentId, string $requestId = null)
    {
        $url = Request::createUrl($this->env, $paymentId, [], true);
        $request = Request::create($this->env, $requestId);
        $request->get($url);
        $request->close();

        $response = $request->getResponse();

        if ($request->getHttpStatus() === Request::HTTP_CODE_UNAUTHORIZED) {
            throw new AuthException('Check your credentials', 7000);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_NOT_FOUND) {
            throw new NotFoundException('Resource Not Found', 7001);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_SERVER_ERROR) {
            throw new RuntimeException('Server is down', 7002);
        }

        $result = new SaleObj();
        $result->populate(json_decode($response));
        return $result;
    }

    /**
     * Lista vendas através do identificador da loja
     *
     * @param string $merchantOrderId
     * @param string|null $requestId Identificador do Request definido pela loja, utilizado quando o lojista usa
     *      diferentes servidores para cada GET/POST/PUT
     *
     * @throws AuthException Caso as credentiais sejam inválidas
     * @throws NotFoundException
     * @throws RuntimeException Caso o servidor esteja fora do ar
     *
     * @return Payment[]
     */
    public function list(string $merchantOrderId, string $requestId = null)
    {
        $url = Request::createUrl($this->env, '', [
            'merchantOrderId' => $merchantOrderId
        ], true);
        $request = Request::create($this->env, $requestId);
        $request->get($url);
        $request->close();

        $response = $request->getResponse();

        if ($request->getHttpStatus() === Request::HTTP_CODE_UNAUTHORIZED) {
            throw new AuthException('Check your credentials', 7000);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_NOT_FOUND) {
            throw new NotFoundException('Resource Not Found', 7001);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_SERVER_ERROR) {
            throw new RuntimeException('Server is down', 7002);
        }

        $payments = [];
        $response = json_decode($response);

        if (isset($response->Payments)) {
            foreach ($response->Payments as $key => $value) {
                $payment = new Payment();
                $payment->populate($value);
                $payments[] = $payment;
            }
        }

        return $payments;
    }

    /**
     * Cancela um pedido
     *
     * @param string $paymentId
     * @param int|null $amount
     * @param string|null $requestId Identificador do Request definido pela loja, utilizado quando o lojista usa
     *      diferentes servidores para cada GET/POST/PUT
     *
     * @throws AuthException Caso as credentiais sejam inválidas
     * @throws NotFoundException
     * @throws RuntimeException Caso o servidor esteja fora do ar
     *
     * @return Object Retorna json decoded
     */
    public function void(string $paymentId, int $amount = null, string $requestId = null)
    {
        $query = [];

        if ($amount !== null) {
            $query['amount'] = $amount;
        }

        $url = Request::createUrl($this->env, $paymentId . '/void', $query);
        $request = Request::create($this->env, $requestId);
        $request->put($url, '{}', true);
        $request->close();

        $response = $request->getResponse();

        if ($request->getHttpStatus() === Request::HTTP_CODE_UNAUTHORIZED) {
            throw new AuthException('Check your credentials', 7000);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_NOT_FOUND) {
            throw new NotFoundException('Resource Not Found', 7001);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_SERVER_ERROR) {
            throw new RuntimeException('Server is down', 7002);
        }

        return json_decode($response);
    }

    /**
     * Captura uma transação
     *
     * @param string $paymentId
     * @param int|null $amount
     * @param int|null $serviceTaxAmount Aplicável para companhias aéreas
     * @param string|null $requestId Identificador do Request definido pela loja, utilizado quando o lojista usa
     *      diferentes servidores para cada GET/POST/PUT
     *
     * @throws AuthException Caso as credentiais sejam inválidas
     * @throws NotFoundException
     * @throws RuntimeException Caso o servidor esteja fora do ar
     * @throws BraspagRequestException
     *
     * @return Object Retorna json decoded
     */
    public function capture(
        string $paymentId,
        int $amount = null,
        int $serviceTaxAmount = null,
        string $requestId = null
    ) {
        $query = [];

        if ($amount !== null) {
            $query['amount'] = $amount;
        }

        if ($serviceTaxAmount !== null) {
            $query['serviceTaxAmount'] = $serviceTaxAmount;
        }

        $url = Request::createUrl($this->env, $paymentId . '/capture', $query);
        $request = Request::create($this->env, $requestId);
        $request->put($url, '{}', true);
        $request->close();

        $response = $request->getResponse();

        if ($request->getHttpStatus() === Request::HTTP_CODE_UNAUTHORIZED) {
            throw new AuthException('Check your credentials', 7000);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_NOT_FOUND) {
            throw new NotFoundException('Resource Not Found', 7001);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_SERVER_ERROR) {
            throw new RuntimeException('Server is down', 7002);
        } elseif ($request->isError()) {
            throw new BraspagRequestException($request, json_encode($query), 'Check your data', 7003);
        }

        return json_decode($response);
    }
}
