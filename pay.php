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
$payment = \PayPal\Api\Payment::get($_GET['paymentId'],$apiContext);

$execution = (new \PayPal\Api\PaymentExecution())
    ->setPayerId($_GET['PayerID'])
    ->addTransaction(\Paiement\TransactionFactory::fromBasket($basket)); //$payment->getTransactions()



try{
    $payment->execute($execution, $apiContext);
    // get transaction numero 1
    //print_r( $payment->getTransactions()[0]->getitemList() );
    $product = [];
    foreach ( $payment->getTransactions()[0]->getitemList()->getItems() as $item) {
       
        
        $iso = [
            "name" => $item->getName(),
            "price" => $item->getPrice()
            // "description" => 
        ];

        array_push($product,$iso);

    }
    //print_r($product);
    echo json_encode([
        'id' => $payment->getId(),
        'items' => $product,
        'state' => $payment->getState(),
    ]);
  }catch( PayPal\Exception\PayPalConnectionException $e){
    header('HTTP 500 Internal Server Error',true,500);
    // echo $e->getMessage();
    var_dump( json_decode( $e->getData()));
  }
  