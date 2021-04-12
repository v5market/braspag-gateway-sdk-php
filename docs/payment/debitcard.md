# Pagamento com cartão de crédito, sem antifraude

Uma transação com um Cartão de Débito é efetuada de forma semelhante à uma do Cartão de Crédito.

## Com autenticação

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    Payment,
    Sale,
    PaymentMethod\DebitCard,
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
$customer->setName('Florbela Espanca Accept');
$customer->setDocument($document);
$customer->setEmail('comprador@braspag.com');
$customer->setBirthdate(DateTime::createFromFormat('Y-m-d', '1993-12-08')); /** Opcional */
$customer->setAddress($address);
$customer->setDeliveryAddress($address);

$payment = new Payment();
$payment->setAuthenticate(true);
$payment->setProvider('Simulado'); /* Verifique o provedor correto para produção */
$payment->setAmount(10000); /** Valor em centavos */
$payment->setCurrency('BRL');
$payment->setCountry('BRA'); /** Utilize o padrão ISO 3166-1 alfa-3 */
$payment->setInstallments(1);
$payment->setReturnUrl('https://www.sualoja.com.br/retorno.php'); /** Obrigatório */

/**
 * Define o tipo de parcelamento
 *   - ByMerchant: Loja
 *   - ByIssuer: Emissor
 */
$payment->setInterest('ByMerchant');
$payment->setCapture(true); /* Deve ser `true`, para captura automática */
$payment->setSoftDescriptor('IdentificaoDaLoja');
$payment->setExtraDataCollection('NomeDoCampo', 'ValorDoCampo'); /** Opcional */

$debitCard = new DebitCard();
$debitCard->setNumber('4111111111111111');
$debitCard->setHolder('Titular do cartão');
$debitCard->setExpirationDate('12/2030');
$debitCard->setSecurityCode('123');
$debitCard->setBrand('Visa');
$payment->setPaymentMethod($debitCard);

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

    'Authentication URL: ' . $result->getPayment()->getAuthenticationUrl(),
    PHP_EOL
]);

echo $result->getResponseRaw();
```

## Sem autenticação

É possível processar um cartão de débito sem a necessidade de submeter o comprador ao processo de autenticação. Confira o artigo [Débito sem Senha (Autenticação)](https://suporte.braspag.com.br/hc/pt-br/articles/360013285531) para mais detalhes a respeito desse tipo de transação.

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    Payment,
    Sale,
    PaymentMethod\DebitCard,
};

/** ... */

$payment = new Payment();
$payment->setAuthenticate(false); /** Adicionar linha */
$payment->setProvider('Simulado');
$payment->setAmount(10000);
$payment->setCurrency('BRL');
$payment->setCountry('BRA');
$payment->setInstallments(1);
$payment->setReturnUrl('https://www.sualoja.com.br/retorno.php');

/** ... */

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