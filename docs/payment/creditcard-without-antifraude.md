# Pagamento com cartão de crédito, sem antifraude

Os dados de `Address` e `DeliveryAddress` são obrigatórios quando a transação é submetida ao AntiFraude ou à análise do Velocity.

 > Caso a sua loja utilize os serviços de Retentaiva ou Loadbalance, as afiliações devem ser cadastradas pela equipe de [suporte ao cliente](http://suporte.braspag.com.br/).

## Modelo padrão

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    Payment,
    Sale,
    PaymentMethod\CreditCard,
};

$address = new Address();
$address->setStreet('Av. Brasil');
$address->setNumber('1234567890');
$address->setComplement('Opcional');
$address->setZipCode('23078001');
$address->setCity('Rio de Janeiro');
$address->setState('RJ');
$address->setCountry('BRA');
$address->setDistrict('Campo Grande');

/**
 * Caso deseje informar os dados de uma pessoa jurídica
 * Utilize ```Document::cnpj('00.000.000/0000-00')```
 */
$document = Document::cpf('000.000.000-00');

$customer = new Customer();
$customer->setName('Florbela Espanca');
$customer->setDocument($document);
$customer->setEmail('comprador@braspag.com');
$customer->setBirthdate(DateTime::createFromFormat('Y-m-d', '1993-12-08')); /** Opcional */
$customer->setAddress($address);
$customer->setDeliveryAddress($address);

$payment = new Payment();
$payment->setProvider('Simulado'); /* Verifique o provedor correto para produção */
$payment->setAmount(10000); /** Valor em centavos */
$payment->setCurrency('BRL');
$payment->setCountry('BRA'); /** Utilize o padrão ISO 3166-1 alfa-3 */
$payment->setInstallments(3);

/**
 * Define o tipo de parcelamento
 *   - ByMerchant: Loja
 *   - ByIssuer: Emissor
 */
$payment->setInterest('ByMerchant');
$payment->setCapture(true); /* Deve ser `true`, para captura automática */
$payment->setSoftDescriptor('IdentificaoDaLoja');
$payment->setExtraDataCollection('NomeDoCampo', 'ValorDoCampo'); /** Opcional */

$creditCard = new CreditCard();
$creditCard->setNumber('4111111111111111');
$creditCard->setHolder('Titular do cartão');
$creditCard->setExpirationDate('12/2030');
$creditCard->setSecurityCode('123');
$creditCard->setBrand('Visa');
$payment->setPaymentMethod($creditCard);

$sale = new Sale();
$sale->setMerchantOrderId('202007041'); /* Número do pedido */
$sale->setCustomer($customer);
$sale->setPayment($payment);

$request = new RequestSale($env);
$result = $request->create($sale);

echo implode(PHP_EOL, [
    'Payment ID: ' . $result->getPayment()->getPaymentId(),
    'Received Date: ' . $result->getPayment()->getReceivedDate()->format('Y-m-d'),
    'Captured Amount: ' . $result->getPayment()->getCapturedAmount(),
    'Reason Code: ' . $result->getPayment()->getReasonCode(),
    'Reason Message: ' . $result->getPayment()->getReasonMessage(),
    'Authorization Code: ' . $result->getPayment()->getAuthorizationCode(),
    PHP_EOL
]);

echo $result->getResponseRaw();
```

## Adquirente GetNet

Os dados do objeto `Credentials` devem, obrigatoriamente, ser enviados se a transação é direcionada para Getnet

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    Payment,
    Sale,
    Payment\Credentials, /** Adicionar */
    PaymentMethod\CreditCard,
};

/** ... */

$credentials = new Credentials();
$credentials->setCode("9999999");
$credentials->setKey("D8888888");
$credentials->setPassword("LOJA9999999");
$credentials->setUsername("#Braspag2020@NOMEDALOJA#");
/**
 * Envio do TerminalID da adquirente Global Payments, ex.: “001”.
 * Para Safra colocar o nome do estabelecimento, cidade e o estado
 * concatenados com ponto-e-vírgula (;), ex.: “NomedaLoja;São Paulo;SP”.
 */
$credentials->setSignature("001");
$payment->setCredentials($credentials);

/** ... */

echo implode(PHP_EOL, [
    'Payment ID: ' . $result->getPayment()->getPaymentId(),
    'Received Date: ' . $result->getPayment()->getReceivedDate()->format('Y-m-d'),
    'Captured Amount: ' . $result->getPayment()->getCapturedAmount(),
    'Reason Code: ' . $result->getPayment()->getReasonCode(),
    'Reason Message: ' . $result->getPayment()->getReasonMessage(),
    'Authorization Code: ' . $result->getPayment()->getAuthorizationCode(),
    PHP_EOL
]);

echo $result->getResponseRaw(); /** @todo Implementar */
```

# Cielo30 ou Rede2

Caso utilize o _provider_ Cielo30 ou Rede2, podes utilizar a classe `SubEstablishment` para informar os dados do estabelecimento.

```php
<?php

require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    Payment,
    Sale,
    Payment\SubEstablishment, /** Adicionar */
    PaymentMethod\CreditCard,
};

/** ... */

$subEstablishment = new SubEstablishment();
$subEstablishment->setEstablishmentCode(100);
$subEstablishment->setMcc(200);
$subEstablishment->setPhoneNumber('71912345678');
$subEstablishment->setCountryCode('BRA');
$subEstablishment->setDocument(Document::cpf('000.000.000-00'));
$subEstablishment->setAddress(
    'Endereço do sub Merchant',
    'Cidade do sub Merchant',
    'Estado do sub Merchant',
    'Código postal do sub Merchant'
);

$payment->setPaymentFacilitator(new PaymentFacilitator(
    'Establishment Code',
    $subEstablishment
));

/** ... */

echo implode(PHP_EOL, [
    'Payment ID: ' . $result->getPayment()->getPaymentId(),
    'Received Date: ' . $result->getPayment()->getReceivedDate()->format('Y-m-d'),
    'Captured Amount: ' . $result->getPayment()->getCapturedAmount(),
    'Reason Code: ' . $result->getPayment()->getReasonCode(),
    'Reason Message: ' . $result->getPayment()->getReasonMessage(),
    'Authorization Code: ' . $result->getPayment()->getAuthorizationCode(),
    PHP_EOL
]);

var_dump($result->getResponseRaw());
```