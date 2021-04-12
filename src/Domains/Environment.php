<?php

namespace Braspag\Gateway\Domains;

class Environment
{
    private $url;
    private $id;
    private $key;
    private $isSandbox;

    /**
     * Define os dados do ambiente de desenvolvimento
     *
     * @param string $id MerchantID ou ClientID
     * @param string $key MerchantKey ou ClientSecret
     *
     * @return self
     */
    public static function sandbox(string $id, string $key): self
    {
        $instance = new self();
        $instance->id = $id;
        $instance->key = $key;
        $instance->isSandbox = true;

        return $instance;
    }

    /**
     * Define os dados do ambiente de produção
     *
     * @param string $id MerchantID ou ClientID
     * @param string $key MerchantKey ou ClientSecret
     *
     * @return self
     */
    public static function production(string $id, string $key): self
    {
        $instance = new self();
        $instance->id = $id;
        $instance->key = $key;
        $instance->isSandbox = false;

        return $instance;
    }

    /**
     * @return string
     */
    public function getMerchantId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getMerchantKey(): string
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getClientId(): string
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getClientSecret(): string
    {
        return $this->key;
    }

    /**
     * @return boolean
     */
    public function isSandbox()
    {
        return $this->isSandbox;
    }

    /**
     * @return string
     */
    public function getBasicAuthorization(): string
    {
        return base64_encode($this->id . ':' . $this->key);
    }
}
