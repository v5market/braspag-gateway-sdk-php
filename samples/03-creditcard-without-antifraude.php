<?php

require_once "../vendor/autoload.php";

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

use Braspag\Gateway\Domains\Environment;

use Braspag\Gateway\Request\Sale as RequestSale;
use Braspag\Gateway\Domains\{
    Address,
    Customer,
    Document,
    Payment,
    Sale,
    Payment\SubEstablishment, /** Adicionar */
    Payment\PaymentFacilitator, /** Adicionar */
    PaymentMethod\CreditCard,
};

$env = Environment::sandbox($_ENV['BRASPAG_MERCHANT_ID'], $_ENV['BRASPAG_MERCHANT_KEY']);

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
$document = Document::cpf('12345678909');

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

$subEstablishment = new SubEstablishment();
$subEstablishment->setEstablishmentCode(100);
$subEstablishment->setMcc(200);
$subEstablishment->setPhoneNumber('71912345678');
$subEstablishment->setCountryCode('BRA');
$subEstablishment->setDocument(Document::cpf('12345678909'));
$subEstablishment->setAddress(
    'Endereço do sub Merchant',
    'Cidade do sub Merchant',
    'Estado do sub Merchant',
    'Código postal do sub Merchant'
);

$payment->setPaymentFacilitator(new PaymentFacilitator(
    123, //'Establishment Code',
    $subEstablishment
));

$sale = new Sale();
$sale->setMerchantOrderId('2021_' . random_int(0, 10000)); /* Número do pedido */
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
