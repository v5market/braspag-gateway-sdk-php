<?php

namespace Braspag\Gateway\Request;

use RuntimeException;
use Braspag\Gateway\Domains\Environment;
use Braspag\Gateway\Exception\Auth as AuthException;
use Braspag\Gateway\Exception\NotFound as NotFoundException;
use Braspag\Gateway\Exception\BraspagRequest as BraspagRequestException;

class ThreeDSecurity
{
    private const URL_ACCESS_TOKEN_SANDBOX = 'https://mpisandbox.braspag.com.br/v2/auth/token';
    private const URL_ACCESS_TOKEN = 'https://mpi.braspag.com.br/v2/auth/token';

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
     * Solicita o token de acesso
     *
     * @throws AuthException Caso as credentiais sejam inválidas
     * @throws NotFoundException
     * @throws RuntimeException Caso o servidor esteja fora do ar
     * @throws BraspagRequestException Caso haja algum dado inválido
     *
     * @return object
     */
    public function generateAccessToken(
        string $establishmentCode,
        string $merchantName,
        string $mcc
    ) {
        if ($this->env->isSandbox()) {
            $url = self::URL_ACCESS_TOKEN_SANDBOX;
        } else {
            $url = self::URL_ACCESS_TOKEN;
        }

        $data = [
            'EstablishmentCode' => $establishmentCode,
            'MerchantName' => $merchantName,
            'MCC' => $mcc,
        ];

        $request = Request::create($this->env);
        $request->setHeader('Authorization', 'Basic ' . $this->env->getBasicAuthorization());
        $request->post($url, $data, true);
        $request->close();

        $response = $request->getResponse();

        if ($request->getHttpStatus() === Request::HTTP_CODE_UNAUTHORIZED) {
            throw new AuthException('Check your credentials', 7000);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_NOT_FOUND) {
            throw new NotFoundException('Resource Not Found', 7001);
        } elseif ($request->getHttpStatus() === Request::HTTP_CODE_SERVER_ERROR) {
            throw new RuntimeException('Server is down', 7002);
        } elseif ($request->isError()) {
            throw new BraspagRequestException($request, $data, 'Check your data', 7003);
        }

        return json_decode($response);
    }
}
