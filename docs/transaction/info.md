# Consultar pagamento

**Regra:**

| Tempo de Vida | Forma de Consulta |
| ------------- | ----------------- |
| até 3 meses | consulta via API/SDK ou Painel Admin Braspag |
| de 3 a 12 meses | somente via consulta no Painel Admin Braspag com a opção “Histórico” selecionada |
| acima de 12 meses | entrar em contato com seu Executivo Comercial Braspag |

## Modelo Padrão

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;

$request = new RequestSale($env);
$result = $request->info($paymentId);

echo implode(PHP_EOL, [
    'Payment ID: ' . $result->getPayment()->getPaymentId(),
    'Received Date: ' . $result->getPayment()->getReceivedDate()->format('Y-m-d'),
    'Captured Amount: ' . $result->getPayment()->getCapturedAmount(),
    'Captured Date: ' . $result->getPayment()->getCapturedDate()->format('d/m/Y'),
    'Reason Code: ' . $result->getPayment()->getReasonCode(),
    'Reason Message: ' . $result->getPayment()->getReasonMessage(),
    'Authorization Code: ' . $result->getPayment()->getAuthorizationCode(),
    'Amount: ' . $result->getPayment()->getAmount(),
    'Status: ' . $result->getPayment()->getStatus(),

    'Merchant Trade ID: ' . $result->getMerchant()->getId(),
    'Merchant Trade Name: ' . $result->getMerchant()->getTradeName(),

    'Proof Of Sale: ' . $result->getPayment()->getProofOfSale(),
    'Acquirer Transaction Id: ' . $result->getPayment()->getAcquirerTransactionId(),
    PHP_EOL
]);

echo $result->getResponseRaw();
```

## Boleto

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;

$request = new RequestSale($env);
$result = $request->info('b8fd02cd-3fc7-4fcb-ab22-86bd62bbc70b');

echo implode(PHP_EOL, [
    'Payment ID: ' . $result->getPayment()->getPaymentId(),
    'Received Date: ' . $result->getPayment()->getReceivedDate()->format('Y-m-d'),
    'Reason Code: ' . $result->getPayment()->getReasonCode(),
    'Reason Message: ' . $result->getPayment()->getReasonMessage(),
    'Authorization Code: ' . $result->getPayment()->getAuthorizationCode(),
    'Amount: ' . $result->getPayment()->getAmount(),
    'Status: ' . $result->getPayment()->getStatus(),

    'Merchant Trade ID: ' . $result->getMerchant()->getId(),
    'Merchant Trade Name: ' . $result->getMerchant()->getTradeName(),

    'Proof Of Sale: ' . $result->getPayment()->getProofOfSale(),
    'Acquirer Transaction Id: ' . $result->getPayment()->getAcquirerTransactionId(),

    /** Somente para boleto */
    'Boleto URL: ' . $result->getPayment()->getPaymentMethod()->getUrl(),
    'Boleto BarCode Number: ' . $result->getPayment()->getPaymentMethod()->getBarCodeNumber(),
    'Boleto Digitable Line: ' . $result->getPayment()->getPaymentMethod()->getDigitableLine(),
    PHP_EOL
]);

echo $result->getResponseRaw();
```