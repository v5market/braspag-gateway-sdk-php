# Pagamento com cartão de crédito, com antifraude

Os dados de `Address` e `DeliveryAddress` são obrigatórios quando a transação é submetida ao AntiFraude ou à análise do Velocity.

 > Caso a sua loja utilize os serviços de Retentativa ou Loadbalance, as afiliações devem ser cadastradas pela equipe de [suporte ao cliente](http://suporte.braspag.com.br/).


## Modelo Padrão

```php
require_once "vendor/autoload.php";

use Braspag\Gateway\Constants\FraudAnalysis\Shipping as ShippingTypes;
use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    FraudAnalysis,
    Payment,
    Sale,
    Cart\Cart,
    Cart\Item as CartItem,
    FraudAnalysis\Browser,
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
$customer->setName('Florbela Espanca Accept');
$customer->setDocument($document);
$customer->setEmail('comprador@braspag.com');
$customer->setBirthdate(DateTime::createFromFormat('Y-m-d', '1993-12-08')); /** Opcional */
$customer->setAddress($address);
$customer->setDeliveryAddress($address);

$payment = new Payment();
$payment->setProvider('Simulado'); /* Verifique o provedor correto para produção */
$payment->setAmount(5480); /** Valor em centavos */
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

$fraudAnalysis = new FraudAnalysis();
$fraudAnalysis->setSequence('AuthorizeFirst'); /** AnalyseFirst ou AuthorizeFirst */
$fraudAnalysis->setSequenceCriteria('OnSuccess'); /** OnSuccess ou Always */
$fraudAnalysis->setProvider('Cybersource');
$fraudAnalysis->setCaptureOnLowRisk(false);
$fraudAnalysis->setVoidOnHighRisk(false);
$fraudAnalysis->setTotalOrderAmount(5480); /** Valor em centavos */
$fraudAnalysis->setFingerPrintId('hash');
$fraudAnalysis->setShipping(
    ShippingTypes::METHOD_SAME_DAY,
    'Nome do Recebedor', /** Opcional */
    '71123456789' /** Telefone (Opcional) */
);

/**
 * Valores possíveis
 * https://braspag.github.io/manual/braspag-pagador#tabela-de-mdds
 */
$fraudAnalysis->setMerchantDefinedFields(1, 'email@comprador.com');
$fraudAnalysis->setMerchantDefinedFields(4, 'web');

$browser = new Browser(
    '127.0.0.1', /** Não pode ser um IP de "localhost" */
    'comprador@braspag.com',
    'Chrome', /** Nome do navegador */
    'Hostname',
    true /** Cookies Accepted */
);
$fraudAnalysis->setBrowser($browser);

/** Produtos */
$productA = new CartItem();
$productA->setName('Antologia poética de Florbela Espanca');
$productA->setQuantity(1);
$productA->setSku('978-8544000342');
$productA->setUnitPrice(3399);

$productB = new CartItem();
$productB->setName('Sonetos');
$productB->setQuantity(1);
$productB->setSku('978-8572329934');
$productB->setUnitPrice(2081);
$productB->setGiftCategory('yes');
/** Opcionais (start) */
$productB->setHostHedge('normal');
$productB->setNonSensicalHedge('normal');
$productB->setObscenitiesHedge('normal');
$productB->setRisk('low');
$productB->setTimeHedge('high');
$productB->setPhoneHedge('off');
$productB->setVelocityHedge('high');
$productB->setType('default');
/** Opcionais (end) */

$cart = new Cart($productA, $productB);
$fraudAnalysis->setCart($cart);
$payment->setFraudAnalysis($fraudAnalysis);

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

**Valores possíveis para os métodos: `setHostHedge`, `setNonSensicalHedge`, `setObscenitiesHedge`, `setRisk`, `setTimeHedge`, `setPhoneHedge`, `setVelocityHedge`**

 - Low
 - Normal
 - High
 - Off

**Valores possíveis para o método `setType`**

 - AdultContent
 - Coupon
 - Default
 - EletronicGood
 - EletronicSoftware
 - GiftCertificate
 - HandlingOnly
 - Service
 - ShippingAndHandling
 - ShippingOnly
 - Subscription