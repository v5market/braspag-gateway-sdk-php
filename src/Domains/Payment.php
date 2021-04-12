<?php

namespace Braspag\Gateway\Domains;

use DateTime;
use Braspag\Gateway\Interfaces\Serializer;
use Braspag\Gateway\Constants\Payment\{
    Currency,
    Interest
};
use Braspag\Gateway\Domains\Payment\{
    Credentials,
    ExternalAuthentication,
    PaymentFacilitator,
    SubEstablishment,
    Refund,
    Chargeback,
    FraudAlert
};
use Braspag\Gateway\Domains\PaymentMethod\{
    AbstractCard,
    CreditCard,
    DebitCard,
    Boleto,
    EletronicTransfer,
    Pix
};

class Payment implements Serializer
{
    /** @var string Payment.Provider */
    private $provider;

    /** @var string Payment.Type */
    private $type;

    /** @var float Payment.Amount */
    private $amount;

    /** @var int Payment.ServiceTaxAmount */
    private $serviceTaxAmount;

    /** @var string Payment.Currency */
    private $currency;

    /** @var string Payment.Country */
    private $country;

    /** @var int Payment.Installments */
    private $installments;

    /** @var string Payment.Interest */
    private $interest;

    /** @var bool Payment.Capture */
    private $capture;

    /** @var bool Payment.Authenticate */
    private $authenticate;

    /** @var ExternalAuthentication Payment.externalAuthentication */
    private $externalAuthentication;

    /** @var bool Payment.Recurrent */
    private $recurrent;

    /** @var string Payment.SoftDescriptor */
    private $softDescriptor;

    /** @var bool Payment.DoSplit */
    private $doSplit;

    /** @var string Payment.ReturnUrl */
    private $returnUrl;

    /** @var array Payment.ExtraDataCollection */
    private $extraDataCollection = [];

    /** @var Credentials Payment.Credentials */
    private $credentials;

    /** @var FraudAnalysis Payment.FraudAnalysis.* */
    private $fraudAnalysis;

    /** @var PaymentFacilitator Payment.PaymentFacilitator */
    private $paymentFacilitator;

    /** @var CreditCard|DebitCard|AbstractCard Payment.CreditCard ou Payment.DebitCard */
    private $card;

    /** @var Boleto Payment.* */
    private $boleto;

    /** @var EletronicTransfer Payment.* */
    private $eletronicTransfer;

    /** @var Pix Payment.* */
    private $pix;

    private $paymentMethod;


    /** @var string Payment.PaymentId */
    private $paymentId;

    /** @var DateTime Payment.ReceivedDate */
    private $receivedDate;

    /** @var DateTime Payment.ReceveidDate */
    private $receveidDate;

    /** @var DateTime Payment.CapturedDate */
    private $capturedDate;

    /** @var int Payment.CapturedAmount */
    private $capturedAmount;

    /** @var string Payment.ECI */
    private $eci;

    /** @var string Payment.ReasonCode */
    private $reasonCode;

    /** @var string Payment.ReasonMessage */
    private $reasonMessage;

    /** @var int Payment.Status */
    private $status;

    /** @var string Payment.ProviderReturnCode */
    private $providerReturnCode;

    /** @var string Payment.ProviderReturnMessage */
    private $providerReturnMessage;

    /** @var string Payment.ProofOfSale */
    private $proofOfSale;

    /** @var string Payment.AuthorizationCode */
    private $authorizationCode;

    /** @var string Payment.AcquirerTransactionId */
    private $acquirerTransactionId;

    /** @var string Payment.AuthenticationUrl */
    private $authenticationUrl;

    /** @var Refund[] Payment.Refunds.* */
    private $refunds;

    /** @var Chargeback[] Payment.Chargebacks[n].* */
    private $chargebacks;

    /** @var FraudAlert Payment.FraudAlert. */
    private $fraudAlert;

    /** @var string Payment.ProviderDescription */
    private $providerDescription;

    /** @var int Payment.VoidedAmount */
    private $voidedAmount;

    /** @var DateTime Payment.VoidedDate */
    private $voidedDate;

    /**
     * Define o nome da provedora de Meio de Pagamento.
     * Atualmente somente a “Cielo” suporta esta forma de pagamento via Pagador
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
     * Define o tipo de pagamento
     *
     * @param string $value
     *
     * @return self
     */
    protected function setType(string $value): self
    {
        $this->type = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Define o valor do pedido (em centavos)
     *
     * @param float $value
     *
     * @return self
     */
    public function setAmount(float $value): self
    {
        $this->amount = $value;
        return $this;
    }

    /**
     * @return float
     */
    public function getAmount(): float
    {
        return $this->amount;
    }

    /**
     * Define montante do valor da autorização que deve ser destinado à taxa de serviço
     *
     * @param int $value
     *
     * @return self
     */
    public function setServiceTaxAmount(int $value): self
    {
        $this->serviceTaxAmount = $value;

        return $this;
    }

    /**
     * @return int
     */
    public function getServiceTaxAmount(): int
    {
        return $this->serviceTaxAmount;
    }

    /**
     * Define a moeda utilizada na compra
     * @see Braspag\Gateway\Constants\Payment\Currency
     *
     * @param string $value
     *
     * @return self
     */
    public function setCurrency(string $value): self
    {
        if (!Currency::has($value) && !Currency::isValidValue($value)) {
            throw new \InvalidArgumentException("Currency $value is invalid", 3000);
        }

        $this->currency = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getCurrency(): string
    {
        return $this->currency;
    }

    /**
     * Define o país da compra
     *
     * @param string $value
     *
     * @return self
     */
    public function setCountry(string $value): self
    {
        if (strlen($value) !== 3) {
            throw new \InvalidArgumentException('The country code must follow the ISO 3166-1 alpha-3 standard', 3001);
        }

        $this->country = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getCountry(): string
    {
        return $this->country;
    }

    /**
     * Define o número de parcelas
     *
     * @param int $value
     *
     * @return self
     */
    public function setInstallments(int $value): self
    {
        $this->installments = $value;
        return $this;
    }

    /**
     * @return int
     */
    public function getInstallments(): int
    {
        return $this->installments;
    }

    /**
     * Tipo de parcelamento
     * Possíveis valores ver em self::INTEREST_*
     *
     * @param string $value
     */
    public function setInterest(string $value): self
    {
        $this->interest = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getInterest(): string
    {
        return $this->interest;
    }

    /**
     * Indica se a autorização deverá ser com captura automática
     *
     * @param bool $value
     */
    public function setCapture(bool $value): self
    {
        $this->capture = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getCapture(): bool
    {
        return $this->capture;
    }

    /**
     * Indica se a transação deve ser autenticada
     *
     * @param bool $value Se não for booleano, converte-o
     */
    public function setAuthenticate(bool $value): self
    {
        $this->authenticate = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getAuthenticate(): bool
    {
        return $this->authenticate;
    }

    /**
     * Define configuração para autenticação externa
     *
     * @param ExternalAuthentication $value
     *
     * @return self
     */
    public function setExternalAuthentication(ExternalAuthentication $value): self
    {
        $this->externalAuthentication = $value;

        return $this;
    }

    /**
     * @return ExternalAuthentication
     */
    public function getExternalAuthentication(): ExternalAuthentication
    {
        return $this->externalAuthentication;
    }

    public function setRecurrent(bool $value): self
    {
        $this->recurrent = $value;

        return $this;
    }

    public function getRecurrent(): bool
    {
        return $this->recurrent;
    }

    /**
     * Texto que será impresso na fatura do portador
     *
     * @param string $value
     */
    public function setSoftDescriptor(string $value): self
    {
        $this->softDescriptor = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getSoftDescriptor(): string
    {
        return $this->softDescriptor;
    }

    /**
     * Indica se a transação será dividida entre vários participantes
     *
     * @param bool $value
     */
    public function setDoSplit(bool $value): self
    {
        $this->doSplit = $value;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getDoSplit(): bool
    {
        return $this->doSplit;
    }

    /**
     * Define a URL para onde o usuário será redirecionado após o fim do pagamento
     *
     * @param string $value
     *
     * @return self
     */
    public function setReturnUrl(string $value): self
    {
        $this->returnUrl = $value;
        return $this;
    }

    /**
     * @return string
     */
    public function getReturnUrl(): string
    {
        return $this->returnUrl;
    }

    /**
     * @return string|null
     */
    public function getPaymentId(): ?string
    {
        return $this->paymentId;
    }

    /**
     * Define um dado extra
     *
     * @param string $key
     * @param string $value
     *
     * @return self
     */
    public function setExtraDataCollection(string $key, string $value): self
    {
        $this->extraDataCollection[] = [
            "Name" => $key,
            "Value" => $value
        ];

        return $this;
    }

    /**
     * @return array
     */
    public function getExtraDataCollection(): array
    {
        return $this->extraDataCollection;
    }

    /**
     * Define as credenciais de pagamento
     *
     * @param Credentials $value
     *
     * @return self
     */
    public function setCredentials(Credentials $value): self
    {
        $this->credentials = $value;
        return $this;
    }

    /**
     * @return Credentials
     */
    public function getCredentials(): Credentials
    {
        return $this->credentials;
    }

    /**
     * Define dados do antifraude
     *
     * @param FraudAnalysis $value
     *
     * @return self
     */
    public function setFraudAnalysis(FraudAnalysis $value): self
    {
        $this->fraudAnalysis = $value;

        return $this;
    }

    /**
     * @return FraudAnalysis
     */
    public function getFraudAnalysis(): FraudAnalysis
    {
        return $this->fraudAnalysis;
    }

    /**
     * Define o Código do estabelecimento do Facilitador
     * e dados do estabelecimento do sub Merchant.
     *
     * @param PaymentFacilitator $value
     */
    public function setPaymentFacilitator(PaymentFacilitator $value): self
    {
        $this->paymentFacilitator = $value;

        return $this;
    }

    /**
     * @return PaymentFacilitator|null
     */
    public function getPaymentFacilitator(): ?PaymentFacilitator
    {
        return $this->paymentFacilitator;
    }

    public function setPaymentMethod($value)
    {
        $type = '';

        if ($value instanceof CreditCard) {
            $type = 'CreditCard';
        } elseif ($value instanceof DebitCard) {
            $type = 'DebitCard';
        } elseif ($value instanceof Boleto) {
            $type = 'Boleto';
        } elseif ($value instanceof EletronicTransfer) {
            $type = 'EletronicTransfer';
        } elseif ($value instanceof Pix) {
            $type = 'Pix';
        } else {
            throw new \InvalidArgumentException('Payment method invalid', 3466);
        }

        $this->setType($type);
        $this->paymentMethod = $value;
    }

    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * @return string
     */
    public function getAcquirerTransactionId(): ?string
    {
        return $this->acquirerTransactionId;
    }

    /**
     * @return string|null
     */
    public function getProofOfSale(): ?string
    {
        return $this->proofOfSale;
    }

    /**
     * @return string|null
     */
    public function getAuthorizationCode(): ?string
    {
        return $this->authorizationCode;
    }

    /**
     * @return DateTime|null
     */
    public function getReceivedDate(): ?DateTime
    {
        return $this->receivedDate;
    }

    /**
     * @return DateTime|null
     */
    public function getReceveidDate(): ?DateTime
    {
        return $this->receveidDate;
    }

    /**
     * @return DateTime|null Retorna a data que a captura foi feita
     */
    public function getCapturedDate(): ?DateTime
    {
        return $this->capturedDate;
    }

    /**
     * @return int|null Retorna a data que a captura foi feita
     */
    public function getCapturedAmount(): ?int
    {
        return $this->capturedAmount;
    }

    /**
     * @return string|null
     */
    public function getEci(): ?string
    {
        return $this->eci;
    }

    /**
     * @return string|null
     */
    public function getReasonCode(): ?string
    {
        return $this->reasonCode;
    }

    /**
     * @return string|null
     */
    public function getReasonMessage(): ?string
    {
        return $this->reasonMessage;
    }

    /**
     * @return int|null
     */
    public function getStatus(): ?int
    {
        return $this->status;
    }

    /**
     * @return string|null
     */
    public function getProviderReturnCode(): ?string
    {
        return $this->providerReturnCode;
    }

    /**
     * @return string
     */
    public function getProviderReturnMessage(): ?string
    {
        return $this->providerReturnMessage;
    }

    /**
     * @return string|null URL de redirecionamento após autenticação bancária
     */
    public function getAuthenticationUrl(): ?string
    {
        return $this->authenticationUrl;
    }

    /**
     * @return Refund[]|null
     */
    public function getRefunds(): ?array
    {
        return $this->refunds;
    }

    /**
     * @return Chargeback[]
     */
    public function getChargebacks(): array
    {
        return $this->chargebacks;
    }

    /**
     * Define dados do FraudAlert
     *
     * @param FraudAlert $value
     *
     * @return self
     */
    public function setFraudAlert(FraudAlert $value): self
    {
        $this->fraudAlert = $value;

        return $this;
    }

    /**
     * Get the value of fraudAlert
     */
    public function getFraudAlert()
    {
        return $this->fraudAlert;
    }

    /**
     * @return string|null
     */
    public function getProviderDescription(): ?string
    {
        return $this->providerDescription;
    }

    /**
     * @return int|null
     */
    public function getVoidedAmount(): ?int
    {
        return $this->voidedAmount;
    }

    /**
     * @return DateTime|null
     */
    public function getVoidedDate(): ?DateTime
    {
        return $this->voidedDate;
    }

    /**
     * {@inheritDoc}
     */
    public function populate(\stdClass $value)
    {
        $this->provider = $value->Provider ?? null;
        $this->type = $value->Type ?? null;
        $this->amount = $value->Amount ?? null;
        $this->serviceTaxAmount = $value->ServiceTaxAmount ?? null;
        $this->currency = $value->Currency ?? null;
        $this->country = $value->Country ?? null;
        $this->installments = $value->Installments ?? null;
        $this->interest = $value->Interest ?? null;
        $this->capture = $value->Capture ?? null;
        $this->authenticate = $value->Authenticate ?? null;
        $this->recurrent = $value->Recurrent ?? null;
        $this->softDescriptor = $value->SoftDescriptor ?? null;
        $this->doSplit = $value->DoSplit ?? null;
        $this->returnUrl = $value->ReturnUrl ?? null;
        $this->extraDataCollection = isset($value->ExtraDataCollection) ? (array) $value->ExtraDataCollection : [];
        $this->paymentId = $value->PaymentId ?? null;
        $this->providerDescription = $value->ProviderDescription ?? null;
        $this->voidedAmount = $value->VoidedAmount ?? null;

        if (isset($value->Credentials)) {
            $credentials = new Credentials();
            $credentials->populate($value->Credentials);
            $this->credentials = $credentials;
        }

        if (isset($value->FraudAnalysis)) {
            $fraudAnalysis = new FraudAnalysis();
            $fraudAnalysis->populate($value->FraudAnalysis);
            $this->fraudAnalysis = $fraudAnalysis;
        }

        if (isset($value->PaymentFacilitator)) {
            $this->paymentFacilitator = new PaymentFacilitator();
            $this->paymentFacilitator->populate($value->PaymentFacilitator);
        }

        $paymentMethod = null;
        $type = strtolower($value->Type ?? '');

        if ($type == 'creditcard') {
            $paymentMethod = new CreditCard();
            $paymentMethod->populate($value->CreditCard);
        } elseif ($type == 'debitcard') {
            $paymentMethod = new DebitCard();
            $paymentMethod->populate($value->DebitCard);
        } elseif ($type == 'boleto') {
            $paymentMethod = new Boleto();
            $paymentMethod->populate($value);
        } elseif ($type == 'eletronictransfer') {
            $paymentMethod = new EletronicTransfer();
            $paymentMethod->populate($value);
        } elseif ($type == 'pix') {
            $paymentMethod = new Pix();
            $paymentMethod->populate($value);
        }

        if ($paymentMethod) {
            $this->paymentMethod = $paymentMethod;
        }

        $this->voidedDate = isset($value->VoidedDate)
            ? DateTime::createFromFormat('Y-m-d H:is:', $value->VoidedDate)
            : null;

        $this->receivedDate = isset($value->ReceivedDate)
            ? DateTime::createFromFormat('Y-m-d H:i:s', $value->ReceivedDate)
            : null;

        $this->receveidDate = isset($value->ReceveidDate)
            ? DateTime::createFromFormat('Y-m-d\TH:i:s.u', $value->ReceveidDate)
            : null;

        $this->capturedDate = isset($value->CapturedDate)
            ? DateTime::createFromFormat('Y-m-d H:i:s', $value->CapturedDate)
            : null;

        $this->eci = $value->ECI ?? null;
        $this->reasonCode = $value->ReasonCode ?? null;
        $this->reasonMessage = $value->ReasonMessage ?? null;
        $this->status = $value->Status ?? null;
        $this->providerReturnCode = $value->ProviderReturnCode ?? null;
        $this->providerReturnMessage = $value->ProviderReturnMessage ?? null;
        $this->proofOfSale = $value->ProofOfSale ?? null;
        $this->authorizationCode = $value->AuthorizationCode ?? null;
        $this->acquirerTransactionId = $value->AcquirerTransactionId ?? null;
        $this->authenticationUrl = $value->AuthenticationUrl ?? null;
        $this->capturedAmount = $value->CapturedAmount ?? null;

        if (isset($value->Refunds)) {
            $this->refunds = [];

            foreach ($value->Refunds as $refund) {
                $refundObj = new Refund();
                $refundObj->populate($refund);
                $this->refunds[] = $refundObj;
            }
        }

        if (isset($value->Chargebacks)) {
            $this->chargebacks = [];

            foreach ($value->Chargebacks as $chargeback) {
                $chargebackObj = new Chargeback();
                $chargebackObj->populate($chargeback);
                $this->chargebacks[] = $chargebackObj;
            }
        }

        if (isset($value->FraudAlert)) {
            $fraudAlert = new FraudAlert();
            $fraudAlert->populate($value->FraudAlert);
            $this->setFraudAlert($fraudAlert);
        }

        if (isset($value->ExternalAuthentication)) {
            $externalAuthentication = new ExternalAuthentication();
            $externalAuthentication->populate($value->ExternalAuthentication);
            $this->setExternalAuthentication($externalAuthentication);
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function toArray(): array
    {
        $arr = get_object_vars($this);
        $result = [];

        foreach ($arr as $key => $value) {
            $key = ucfirst($key);
            $keya = ucfirst($key);

            if ($value instanceof CreditCard) {
                $key = 'CreditCard';
            } elseif ($value instanceof DebitCard) {
                $key = 'DebitCard';
            } elseif ($value instanceof Boleto) {
                $result = array_merge($result, $value->toArray());
                $value = null;
            } elseif ($value instanceof EletronicTransfer) {
                $result = array_merge($result, $value->toArray());
                $value = null;
            } elseif ($value instanceof Pix) {
                $result = array_merge($result, $value->toArray());
                $value = null;
            }

            if ($value instanceof Serializer) {
                $value = $value->toArray();
            } elseif ($value instanceof DateTime) {
                $value = $value->format('Y-m-d H:i:s');
            }

            $result[$key] = $value;
        }

        return array_filter($result, function ($item) {
            return $item !== null && $item !== [];
        });
    }

    /**
     * {@inheritDoc}
     */
    public function jsonSerialize(): array
    {
        return $this->toArray();
    }
}
