# Tratamento de exceções

Para tratar exceções utilize o bloco `try..catch`.

Exemplo do tratamento de exceções *LengthException* e *InvalidArgumentException*:

```php
try {
  /** Code here */

  $sale = new Sale();
  $sale->setMerchantOrderId('202007041'); /* Número do pedido */
  $sale->setCustomer($customer);
  $sale->setPayment($payment);

} catch (LengthException | InvalidArgumentException $e) {
  $error = [
    '--------------------------',
    'Code: ' . $e->getCode(),
    'Message: ' . $e->getMessage()
    '--------------------------'
  ];

  die(implode(PHP_EOL, $error));
}
```

As exceções supra são invocadas quando há erro de validação nos dados do pedido, como CPF inválido, por exemplo.

Exemplo do tratamento de exceções *BraspagGatewayException*:

```php
use Braspag\Gateway\Exception\Auth as AuthException;
use Braspag\Gateway\Exception\NotFound as NotFoundException;
use Braspag\Gateway\Exception\BraspagRequest as BraspagRequestException;

use Braspag\Gateway\Request\Sale as RequestSale;

try {
  $request = new RequestSale($env);
  $result = $request->info('payment-id');

} catch (AuthException $e) {
  die('Invalid credentials');
} catch (NotFoundExcepetion $e) {
  die('Order not found');
} catch (BraspagRequestException $e) {
  $error = [
    '--------------------------',
    'Error while sending data',
    'Status Code: ' . $e->getStatusCode(),
    'Response: ' . $e->getResponse()
    '--------------------------'
  ];

  die(implode(PHP_EOL, $error));
} catch (Exception $e) {
  $error = [
    '--------------------------',
    'Unexpected Error',
    'Code: ' . $e->getCode(),
    'Message: ' . $e->getMessage(),
    'File: ' . $e->getFile(),
    'Line: ' . $e->getLine(),
    '--------------------------'
  ];

  die(implode(PHP_EOL, $error));
}
```