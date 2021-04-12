<?php

namespace Braspag\Gateway\Domains;

use Braspag\Gateway\Domains\FraudAnalysis\Browser;
use Braspag\Gateway\Domains\FraudAnalysis\Travel;
use Braspag\Gateway\Domains\FraudAnalysis\ReplyData;
use Braspag\Gateway\Domains\Cart\Cart;
use Braspag\Gateway\Interfaces\Serializer;
use Braspag\Gateway\Constants\FraudAnalysis\Shipping as ShippingConstants;

class FraudAnalysis implements Serializer
{
    /** @var string Payment.FraudAnalysis.Sequence */
    private $sequence;

    /** @var string Payment.FraudAnalysis.SequenceCriteria */
    private $sequenceCriteria;

    /** @var string Payment.FraudAnalysis.Provider */
    private $provider;

    /** @var bool Payment.FraudAnalysis.CaptureOnLowRisk */
    private $captureOnLowRisk;

    /** @var bool Payment.FraudAnalysis.VoidOnHighRisk */
    private $voidOnHighRisk;

    /** @var int Payment.FraudAnalysis.TotalOrderAmount */
    private $totalOrderAmount;

    /** @var string Payment.FraudAnalysis.FingerPrintId */
    private $fingerPrintId;

    /** @var Browser Payment.FraudAnalysis.Browser.* */
    private $browser;

    /** @var Cart Payment.FraudAnalysis.Cart.* */
    private $cart;

    /** @var array Payment.FraudAnalysis.MerchantDefinedFields.* */
    private $merchantDefinedFields = [];

    /** @var array Payment.FraudAnalysis.Shipping.* */
    private $shipping;

    /** @var Travel Payment.FraudAnalysis.Travel.* */
    private $travel;


    /** @var string Payment.FraudAnalysis.Id */
    private $id;

    /** @var int Payment.FraudAnalysis.Status */
    private $status;

    /** @var int Payment.FraudAnalysis.FraudAnalysisReasonCode */
    private $fraudAnalysisReasonCode;

    /** @var ReplyData Payment.FraudAnalysis.ReplyData.* */
    private $replyData;

    /**
     * Define o tipo de fluxo da análise de fraude
     *
     * @param string $value
     *
     * @return self
     */
    public function setSequence(string $value): self
    {
        $this->sequence = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getSequence(): string
    {
        return $this->sequence;
    }

    /**
     * Define o critério do fluxo da análise de fraude
     *
     * @param string $value
     *
     * @return self
     */
    public function setSequenceCriteria(string $value): self
    {
        $this->sequenceCriteria = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getSequenceCriteria(): string
    {
        return $this->sequenceCriteria;
    }

    /**
     * Define o provedor de AntiFraude
     *
     * @param string $value
     *
     * @return self
     */
    public function setProvider(string $value): self
    {
        $this->provider = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getProvider(): string
    {
        return $this->provider;
    }

    /**
     * Indica se a transação após a análise de fraude será capturada
     *
     * @param bool $value
     *
     * @return self
     */
    public function setCaptureOnLowRisk(bool $value): self
    {
        $this->captureOnLowRisk = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getCaptureOnLowRisk(): bool
    {
        return $this->captureOnLowRisk;
    }

    /**
     * Indica se a transação após a análise de fraude será cancelada
     *
     * @param bool $value
     *
     * @return self
     */
    public function setVoidOnHighRisk(bool $value): self
    {
        $this->voidOnHighRisk = $value;

        return $this;
    }

    /**
     * @return bool
     */
    public function getVoidOnHighRisk(): bool
    {
        return $this->voidOnHighRisk;
    }

    /**
     * Define o valor total do pedido em centavos
     *
     * @param int $value
     *
     * @return self
     */
    public function setTotalOrderAmount(int $value): self
    {
        $this->totalOrderAmount = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getTotalOrderAmount(): int
    {
        return $this->totalOrderAmount;
    }

    /**
     * Define o identificador utilizado para cruzar informações obtidas do dispositivo do comprador
     *
     * @param string $value
     *
     * @return self
     */
    public function setFingerPrintId(string $value): self
    {
        $this->fingerPrintId = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getFingerPrintId(): string
    {
        return $this->fingerPrintId;
    }

    /**
     * Define as configurações do navegador
     *
     * @param Browser $value
     *
     * @return self
     */
    public function setBrowser(Browser $value): self
    {
        $this->browser = $value;

        return $this;
    }

    /**
     * @return Browser
     */
    public function getBrowser(): Browser
    {
        return $this->browser;
    }

    /**
     * Define as configurações do carrinho e os item comprados
     *
     * @param Cart $value
     *
     * @return self
     */
    public function setCart(Cart $value): self
    {
        $this->cart = $value;

        return $this;
    }

    /**
     * @return Cart
     */
    public function getCart(): Cart
    {
        return $this->cart;
    }

    /**
     * Adiciona as informações adicionais a serem enviadas
     *
     * @param mixed $id
     * @param mixed $value
     *
     * @return self
     */
    public function setMerchantDefinedFields($id, $value): self
    {
        $this->merchantDefinedFields[] = [
            "Id" => $id,
            "Value" => $value
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getMerchantDefinedFields(): array
    {
        return $this->merchantDefinedFields;
    }

    /**
     * Define os dados de entrega
     *
     * @param string $method Meio de entrega do pedido
     * @param string|null $addressee Nome completo do responsável a receber o produto no endereço de entrega
     * @param string|null $phone Número do telefone do responsável a receber o produto no endereço de entrega
     *
     * @return self
     */
    public function setShipping(string $method, ?string $addressee = null, ?string $phone = null): self
    {
        $this->shipping = [
            "Method" => $method,
        ];

        if ($addressee) {
            $this->shipping["Addressee"] = $addressee;
        }

        if ($phone) {
            $this->shipping["Phone"] = $phone;
        }

        return $this;
    }

    public function getShipping(): array
    {
        return $this->shipping;
    }

    /**
     * Define as configurações da passagem aérea
     *
     * @param Travel $value
     *
     * @return self
     */
    public function setTravel(Travel $value): self
    {
        $this->travel = $value;

        return $this;
    }

    /**
     * @return Travel
     */
    public function getTravel(): Travel
    {
        return $this->travel;
    }

    /**
     * @return string Id da transação no AntiFraude Braspag
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @return int Status da transação no AntiFraude Braspag
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return int Código de retorno da Cybersouce
     */
    public function getFraudAnalysisReasonCode(): ?int
    {
        return $this->fraudAnalysisReasonCode;
    }

    /**
     * @return ReplyData Resposta do antifraude
     */
    public function getReplyData(): ?ReplyData
    {
        return $this->replyData;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->sequence = $value->Sequence ?? null;
        $this->sequenceCriteria = $value->SequenceCriteria ?? null;
        $this->provider = $value->Provider ?? null;
        $this->captureOnLowRisk = $value->CaptureOnLowRisk ?? null;
        $this->voidOnHighRisk = $value->VoidOnHighRisk ?? null;
        $this->totalOrderAmount = $value->TotalOrderAmount ?? null;
        $this->fingerPrintId = $value->FingerPrintId ?? null;

        if (isset($value->Browser)) {
            $this->browser = new Browser();
            $this->browser->populate($value->Browser);
        }

        if (isset($value->Cart)) {
            $this->cart = new Cart();
            $this->cart->populate($value->Cart);
        }

        if (isset($value->MerchantDefinedFields)) {
            foreach ($value->MerchantDefinedFields as $field) {
                $this->setMerchantDefinedFields($field->Id, $field->Value);
            }
        }

        if (isset($value->Shipping)) {
            $this->setShipping(
                $value->Shipping->Method,
                $value->Shipping->Addressee ?? null,
                $value->Shipping->Phone ?? null
            );
        }

        if (isset($value->Travel)) {
            $this->travel = new Travel();
            $this->travel->populate($value->Travel);
        }

        $this->id = $value->Id ?? null;
        $this->status = $value->Status ?? null;
        $this->fraudAnalysisReasonCode = $value->FraudAnalysisReasonCode ?? null;

        if (isset($value->ReplyData)) {
            $this->replyData = new ReplyData();
            $this->replyData->populate($value->ReplyData);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $objectValues = get_object_vars($this);

        $result = [];

        foreach ($objectValues as $key => $value) {
            if ($value === null) {
                continue;
            }

            $key = ucfirst($key);

            if ($value instanceof Serializer) {
                $result[$key] = $value->toArray();
            } else {
                $result[$key] = $value;
            }
        }

        return $result;
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
