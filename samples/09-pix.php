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
};
use Braspag\Gateway\Domains\PaymentMethod\Pix;

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
$payment->setProvider('Cielo30'); /* Verifique o provedor correto para produção */
$payment->setPaymentMethod(new Pix());
$payment->setAmount(10000); /** Valor em centavos */
$payment->setCurrency('BRL');
$payment->setCountry('BRA'); /** Utilize o padrão ISO 3166-1 alfa-3 */

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

    'QRCode Image: ' . $result->getPayment()->getPaymentMethod()->getQrcodeBase64Image(),
    'QRCode String: ' . $result->getPayment()->getPaymentMethod()->getQrCodeString(),
    PHP_EOL
]);

echo $result->getResponseRaw();
