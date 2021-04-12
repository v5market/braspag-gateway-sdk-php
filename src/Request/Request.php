<?php

namespace Braspag\Gateway\Request;

use Curl\Curl;
use Braspag\Gateway\Domains\Environment;

class Request
{
    private const URL_SANDBOX = 'https://apisandbox.braspag.com.br/v2/sales/';
    private const URL_PRODUCTION = 'https://api.braspag.com.br/v2/sales/';

    private const URL_QUERY_SANDBOX = 'https://apiquerysandbox.braspag.com.br/v2/sales/';
    private const URL_QUERY_PRODUCTION = 'https://apiquery.braspag.com.br/v2/sales/';

    public const USER_AGENT = 'Braspag Gateway SDK v0.0.1';

    public const HTTP_CODE_UNAUTHORIZED = 401;
    public const HTTP_CODE_NOT_FOUND = 404;
    public const HTTP_CODE_SERVER_ERROR = 500;

    private function __construct()
    {
        /** Previning */
    }

    /**
     * Cria e configura uma instância de Curl
     *
     * @param Environment $env
     * @param string|null $requestId Identificador do Request definido pela loja, utilizado quando o lojista usa
     *      diferentes servidores para cada GET/POST/PUT
     *
     * @return Curl
     */
    public static function create(Environment $env, string $requestId = null): Curl
    {
        $instance = new Curl();
        $instance->setUserAgent(self::USER_AGENT);
        $instance->setOpt(CURLOPT_SSL_VERIFYPEER, !$env->isSandbox());
        $instance->setHeader('Content-Type', 'application/json');
        $instance->setHeader('MerchantId', $env->getMerchantId());
        $instance->setHeader('MerchantKey', $env->getMerchantKey());

        if ($requestId !== null) {
            $instance->setHeader('RequestId', $requestId);
        }

        return $instance;
    }

    /**
     * Cria uma URL conforme o ambiente informado
     *
     * @param Environment $env
     * @param string $path
     * @param string[] $parameters
     * @param bool $isQuery
     *
     * @return string
     */
    public static function createUrl(
        Environment $env,
        string $path = '',
        array $parameters = [],
        bool $isQuery = false
    ): string {
        if ($isQuery) {
            $url_sandbox = self::URL_QUERY_SANDBOX;
            $url_production = self::URL_QUERY_PRODUCTION;
        } else {
            $url_sandbox = self::URL_SANDBOX;
            $url_production = self::URL_PRODUCTION;
        }

        return sprintf(
            '%s%s?%s',
            $env->isSandbox() ? $url_sandbox : $url_production,
            ltrim($path, '/'),
            http_build_query($parameters)
        );
    }
}
