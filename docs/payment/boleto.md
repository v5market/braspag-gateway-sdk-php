# Boleto

Para gerar um boleto, inclusive em ambiente de Sandbox, é necessário fornecer dados do comprador como CPF ou CNPJ e endereço. 

 > Todos os boletos emitidos no e-commerce, obrigatoriamente, terão de ser registrados.

## Modelo Padrão

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    Payment,
    Sale,
    PaymentMethod\Boleto,
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
$payment->setReturnUrl('https://www.sualoja.com.br/retorno.php'); /** Obrigatório */

$boleto = new Boleto();
$boleto->setBoletoNumber('2020090901');
$boleto->setAssignor('Empresa Teste');
$boleto->setDemonstrative('Desmonstrative Teste');
$boleto->setExpirationDate(DateTime::createFromFormat('Y-m-d', '2030-12-31'));
$boleto->setIdentification('00.000.000/0000-00');
$boleto->setInstructions('Aceitar somente até a data de vencimento.');
$payment->setPaymentMethod($boleto);

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

    'Boleto URL: ' . $result->getPayment()->getPaymentMethod()->getUrl(),
    PHP_EOL
]);

echo $result->getResponseRaw();
```

## Com *provider* Bradesco2

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    Payment,
    Sale,
    PaymentMethod\Boleto,
};

/** ... */

$boleto = new Boleto();
$boleto->setBoletoNumber('2020090901');
$boleto->setAssignor('Empresa Teste');
$boleto->setDemonstrative('Desmonstrative Teste');
$boleto->setExpirationDate(DateTime::createFromFormat('Y-m-d', '2030-12-31'));
$boleto->setIdentification('00.000.000/0000-00');
$boleto->setInstructions('Aceitar somente até a data de vencimento.');

/** Quantidade de dias após o vencimento para cobrar o valor da multa (Opcional) */
$boleto->setDaysToFine(1);

/** Valor da multa após o vencimento em percentual, com base no valor do boleto (Opcional) */
$boleto->setFineRate(10.00000);

/** Valor da multa após o vencimento em valor absoluto em centavos. Não enviar se utilizar FineRate. */
$boleto->setFineAmount(1000);

/** Quantidade de dias após o vencimento para iniciar a cobrança de juros por dia sobre o valor do boleto */
$boleto->setDaysToInterest(1);

/** Valor de juros mensal após o vencimento em percentual, com base no valor do boleto. Não enviar se utilizar InterestAmount. (Opcional) */
$boleto->setInterestRate(5.00000);

/** Valor absoluto de juros diário após o vencimento em centavos. Não enviar se utilizar InterestRate. (Opcional) */
$boleto->setInterestAmount(500);
$payment->setPaymentMethod($boleto);

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

    'Boleto URL: ' . $result->getPayment()->getPaymentMethod()->getUrl(),
    PHP_EOL
]);

echo $result->getResponseRaw();
```
