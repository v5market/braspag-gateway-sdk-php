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
    PaymentMethod\DebitCard,
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

    'Authentication URL: ' . $result->getPayment()->getAuthenticationUrl(),
    PHP_EOL
]);

echo $result->getResponseRaw();
