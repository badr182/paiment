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
// vérifier que tout correspond 
$payment = \PayPal\Api\Payment::get($_GET['paymentID'],$apiContext);

$execution = (new \PayPal\Api\PaymentExecution())
    ->setPayerId($_GET['payerID'])
    ->addTransaction(\Paiement\TransactionFactory::fromBasket($basket)); //$payment->getTransactions()



try{
    $payment->execute($execution, $apiContext);
    // get transaction numero 1
    //var_dump( $payment->getTransactions()[0]->getCustom() );
    //var_dump( $payment );

    echo json_encode([
        'id' => $payment->getId()
    ]);
  }catch( PayPal\Exception\PayPalConnectionException $e){
    header('HTTP 500 Internal Server Error',true,500);
    // echo $e->getMessage();
    var_dump( json_decode( $e->getData()) );
  }
  