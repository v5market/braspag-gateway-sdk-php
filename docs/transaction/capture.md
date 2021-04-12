# Capturando transação

Quando uma transação é submetida com o parâmetro `Payment.Capture` igual a *“false”*, é necessário que seja feita, posteriormente, uma solicitação de captura para confirmar a transação.

Transações que não são capturadas até a [data limite](https://suporte.braspag.com.br/hc/pt-br/articles/360028661812-Prazos-de-captura-e-estorno) são automaticamente desfeitas pelas adquirentes. Clientes podem ter negociações específicas com as adquirentes para que alterem esse prazo limite de captura.

```php
use Braspag\Gateway\Request\Sale as RequestSale;

$request = new RequestSale($env);
$result = $request->capture(
    $paymentId,
    $amount,
    $serviceTaxAmount, // Aplicável para companhias aéreas
);

echo implode(PHP_EOL, [
    'Status: ' . $result->Status,
    'Reason Code: ' . $result->ReasonCode,
    'Reason Message: ' . $result->ReasonMessage,
    'Provider Return Code: ' . $result->ProviderReturnCode,
    'Provider Return Message: ' . $result->ProviderReturnMessage,
    PHP_EOL
  ]);

  var_dump($result);
```