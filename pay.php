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

var_dump($payment);