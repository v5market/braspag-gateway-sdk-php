# Cancelando/Estornando uma transação

Cada adqurirente tem seus prazos limites para permitir o estorno de uma transação. No artigo [Prazos de captura e estorno](https://suporte.braspag.com.br/hc/pt-br/articles/360028661812-Prazos-de-captura-e-estorno), você poderá conferir cada um deles.

 > A disponibilidade do serviço de Estorno varia de adquirente para adquirente.

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;

$request = new RequestSale($env);
$result = $request->void($paymentId);

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