<?php
// initialzer un paiement  

require 'vendor/autoload.php';
$ids = require 'paypal.php' ;

$basket = \Paiement\Basket::fake();

// permet de sauvgarder les identifiant 
$apiContext = new \PayPal\Rest\ApiContext(
    new \PayPal\Auth\OAuthTokenCredential(
        $ids['id'],
        $ids['secret']
    )
);


$payment = new \PayPal\Api\Payment();
//$payment->setTransactions([$transaction]);
$payment->addTransaction(\Paiement\TransactionFactory::fromBasket($basket) );

// setIntent:  intent 
/*
intent enum required
The payment intent. Value is:
sale. Makes an immediate payment.
authorize. Authorizes a payment for capture later.
order. Creates an order.
 */
$payment->setIntent('sale'); // definir a vente 
// redirect url lorsque le paiement sera accepter 
$redirectUrls = new \PayPal\Api\RedirectUrls(); // utilisation d'enchainemment 
//  url de retour 
$redirectUrls->setReturnUrl('http://localhost:8000/pay.php');
$redirectUrls->setCancelUrl('http://localhost:8000/index.php');

$payment->setRedirectUrls($redirectUrls);

// utiliser carte bleue ???
$payment->setPayer( (new \PayPal\Api\Payer())->setPaymentMethod('paypal')  );

try{
  $payment->create($apiContext);
  // rediriger le client pour accepter le paiement
  //echo $payment->getApprovalLink();
  header('Location: '. $payment->getApprovalLink());
}catch( PayPal\Exception\PayPalConnectionException $e){
  // echo $e->getMessage();
  var_dump( json_decode( $e->getData()) );
}

// definir le payeur 
/*
{
    "intent": "sale",
    "payer": {
      "payment_method": "paypal"
    },
    "transactions": [{
      "amount": {
        "total": "30.11",
        "currency": "USD",
        "details": {
          "subtotal": "30.00",
          "tax": "0.07",
          "shipping": "0.03",
          "handling_fee": "1.00",
          "shipping_discount": "-1.00",
          "insurance": "0.01"
        }
      },
      "description": "This is the payment transaction description.",
      "custom": "EBAY_EMS_90048630024435",
      "invoice_number": "48787589673",
      "payment_options": {
        "allowed_payment_method": "INSTANT_FUNDING_SOURCE"
      },
      "soft_descriptor": "ECHI5786786",
      "item_list": {
        "items": [{
          "name": "hat",
          "description": "Brown color hat",
          "quantity": "5",
          "price": "3",
          "tax": "0.01",
          "sku": "1",
          "currency": "USD"
        }, {
          "name": "handbag",
          "description": "Black color hand bag",
          "quantity": "1",
          "price": "15",
          "tax": "0.02",
          "sku": "product34",
          "currency": "USD"
        }],
        "shipping_address": {
          "recipient_name": "Hello World",
          "line1": "4thFloor",
          "line2": "unit#34",
          "city": "SAn Jose",
          "country_code": "US",
          "postal_code": "95131",
          "phone": "011862212345678",
          "state": "CA"
        }
      }
    }],
    "note_to_payer": "Contact us for any questions on your order.",
    "redirect_urls": {
      "return_url": "https://example.com",
      "cancel_url": "https://example.com"
    }
  }
 */