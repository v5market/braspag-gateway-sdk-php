# Formas de autenticação

Quando uma transação é submetida ao processo de autenticação, o portador será redirecionado ao ambiente do emissor, onde deverá realizar a confirmação de seus dados. Quando validado corretamente, o risco de chargeback da transação passa a ser do emissor, ou seja, a loja não receberá contestações.

Existem duas maneiras para autenticar transações na Braspag:

 - **Padrão**: quando o lojista não possui uma conexão direta com um autenticador (MPI) e espera que o meio de pagamento redirecione o cliente para o ambiente de autenticação.
 
 - **Externa**: quando o lojista possui um autenticador próprio (MPI) e não espera que o meio de pagamento redirecione seu consumidor para o ambiente de autenticação.

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
    PaymentMethod\DebitCard,
};

/** ... */

/** Autenticação **/
$payment->setAuthenticate(true);
$payment->setReturnUrl('https://www.sua-loja.com.br/');

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

var_dump($result->getResponseRaw());
```

## Modelo Externo

 > Este fluxo é suportado pelas adquirentes Cielo, Global Payments e Banorte.

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    Payment,
    Sale,
    Payment\ExternalAuthentication, /** Adicionar */
    PaymentMethod\DebitCard,
};

/** ... */

/** Autenticação **/
$payment->setAuthenticate(true);

$externalAuthentication = new ExternalAuthentication();
$externalAuthentication->setCavv('AAABB2gHA1B5EFNjWQcDAAAAAAB=');
$externalAuthentication->setXid('Uk5ZanBHcWw2RjRCbEN5dGtiMTB=');
$externalAuthentication->setEci(5);
$externalAuthentication->setVersion(2); /** Versão do 3DS 1 - 1.0 ; 2 - 2.0 */
$payment->setExternalAuthentication($externalAuthentication);

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

var_dump($result->getResponseRaw());
```

!> **Atenção**<br>
Acesse o [manual de integração via JavaScript](https://braspag.github.io/manual/integracao-javascript) para saber como obter os valores `Cavv`, `Xid`, `ECI`, `Version` e `Reference ID`.