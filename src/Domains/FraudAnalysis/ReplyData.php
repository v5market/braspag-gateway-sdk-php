<?php

namespace Braspag\Gateway\Domains\FraudAnalysis;

use Braspag\Gateway\Interfaces\Serializer;

class ReplyData implements Serializer
{
    /** @var string Payment.FraudAnalysis.ReplyData.AddressInfoCode */
    private $addressInfoCode;

    /** @var string Payment.FraudAnalysis.ReplyData.FactorCode */
    private $factorCode;

    /** @var string Payment.FraudAnalysis.ReplyData.Score */
    private $score;

    /** @var string Payment.FraudAnalysis.ReplyData.BinCountry */
    private $binCountry;

    /** @var string Payment.FraudAnalysis.ReplyData.CardIssuer */
    private $cardIssuer;

    /** @var string Payment.FraudAnalysis.ReplyData.CardScheme */
    private $cardScheme;

    /** @var int Payment.FraudAnalysis.ReplyData.HostSeverity */
    private $hostSeverity;

    /** @var string Payment.FraudAnalysis.ReplyData.InternetInfoCode */
    private $internetInfoCode;

    /** @var string Payment.FraudAnalysis.ReplyData.IpRoutingMethod */
    private $ipRoutingMethod;

    /** @var string Payment.FraudAnalysis.ReplyData.ScoreModelUsed */
    private $scoreModelUsed;

    /** @var int Payment.FraudAnalysis.ReplyData.CasePriority */
    private $casePriority;

    /** @var string Payment.FraudAnalysis.ReplyData.ProviderTransactionId */
    private $providerTransactionId;

    /**
     * Códigos indicam incompatibilidades entre os endereços de cobrança e entrega do comprador
     *
     * @return string
     */
    public function getAddressInfoCode()
    {
        return $this->addressInfoCode;
    }

    /**
     * Códigos que afetaram a pontuação da análise
     *
     * @return string
     */
    public function getFactorCode()
    {
        return $this->factorCode;
    }

    /**
     * Score da análise de fraude. Valor entre 0 e 100
     *
     * @return mixed
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Código do país do BIN do cartão usado na análise.
     * Mais informações em [ISO 2-Digit Alpha Country Code](https://www.iso.org/obp/ui)
     *
     * @return string
     */
    public function getBinCountry()
    {
        return $this->binCountry;
    }

    /**
     * Nome do banco ou entidade emissora do cartão de crédito
     *
     * @return string
     */
    public function getCardIssuer()
    {
        return $this->cardIssuer;
    }

    /**
     * Bandeira do cartão
     *
     * @return string
     */
    public function getCardScheme()
    {
        return $this->cardScheme;
    }

    /**
     * Nível de risco do domínio de e-mail do comprador, de 0 a 5, onde 0 é
     * risco indeterminado e 5 representa o risco mais alto
     *
     * @return integer
     */
    public function getHostSeverity()
    {
        return $this->hostSeverity;
    }

    /**
     * Códigos que indicam problemas com o endereço de e-mail, o endereço IP
     * ou o endereço de cobrança
     *
     * @return string
     */
    public function getInternetInfoCode()
    {
        return $this->internetInfoCode;
    }

    /**
     * Método de roteamento do comprador obtido a partir do endereço de IP
     *
     * @return string
     */
    public function getIpRoutingMethod()
    {
        return $this->ipRoutingMethod;
    }

    /**
     * Nome do modelo de score utilizado na análise. Caso não tenha nenhum modelo
     * definido, o modelo padrão da Cybersource foi o utilizado
     *
     * @return string
     */
    public function getScoreModelUsed()
    {
        return $this->scoreModelUsed;
    }

    /**
     * Define o nível de prioridade das regras ou perfis do lojista. O nível de
     * prioridade varia de 1 (maior) a 5 (menor) e o valor padrão é 3, e este
     * será atribuído caso não tenha definido a prioridade das regras ou perfis.
     * Este campo somente será retornado se a loja for assinante do Enhanced Case
     * Management
     *
     * @return integer
     */
    public function getCasePriority()
    {
        return $this->casePriority;
    }

    /**
     * Id da transação na Cybersource
     *
     * @return string
     */
    public function getProviderTransactionId()
    {
        return $this->providerTransactionId;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->addressInfoCode = $value->AddressInfoCode ?? null;
        $this->factorCode = $value->FactorCode ?? null;
        $this->score = $value->Score ?? null;
        $this->binCountry = $value->BinCountry ?? null;
        $this->cardIssuer = $value->CardIssuer ?? null;
        $this->cardScheme = $value->CardScheme ?? null;
        $this->hostSeverity = $value->HostSeverity ?? null;
        $this->internetInfoCode = $value->InternetInfoCode ?? null;
        $this->ipRoutingMethod = $value->IpRoutingMethod ?? null;
        $this->scoreModelUsed = $value->ScoreModelUsed ?? null;
        $this->casePriority = $value->CasePriority ?? null;
        $this->providerTransactionId = $value->ProviderTransactionId ?? null;

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
    public function jsonSerialize()
    {
        return $this->toArray();
    }
}
