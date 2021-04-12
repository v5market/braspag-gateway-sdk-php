# Consultando uma venda pelo identificador da loja

**Regra:**

| Tempo de Vida | Forma de Consulta |
| ------------- | ----------------- |
| até 3 meses | consulta via API/SDK ou Painel Admin Braspag |
| de 3 a 12 meses | somente via consulta no Painel Admin Braspag com a opção “Histórico” selecionada |
| acima de 12 meses | entrar em contato com seu Executivo Comercial Braspag |

Não é possível consultar diretamente uma pagamento pelo identificador enviado pela loja (MerchantOrderId), mas é possível obter todos os PaymentIds associados ao identificador.

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;

$request = new RequestSale($env);
$result = $request->list('2017051002');

foreach ($result as $value) {
    echo $value->getPaymentId() . PHP_EOL;
}
```