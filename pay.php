<?php

require 'vendor/autoload.php';

$ids = require 'paypal.php';

// $basket = \Paiement\Basket::fake();
// permet de sauvgarder les identifiant
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        $ids['id'],
        $ids['secret']
    )
);
$basket = \Paiement\Basket::fake();
$payment = \PayPal\Api\Payment::get($_GET['paymentId'],$apiContext);

$execution = (new \PayPal\Api\PaymentExecution())
    ->setPayerId($_GET['PayerID'])
    ->setTransactions($payment->getTransactions());



try{
    $payment->execute($execution, $apiContext);
    // get transaction numero 1
    var_dump( $payment->getTransactions()[0]->getCustom() );
    var_dump( $payment );
  }catch( PayPal\Exception\PayPalConnectionException $e){
    // echo $e->getMessage();
    var_dump( json_decode( $e->getData()) );
  }
  