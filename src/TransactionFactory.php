<?php
namespace Paiement ;

use PayPal\Api\Transaction ;

class TransactionFactory{

        // prend un panier est convertir on transaction  paypal 
    static function fromBasket(Basket $basket, float $vatRate = 0):Transaction {
        
        $list = new \PayPal\Api\ItemList();
        foreach( $basket->getProducts() as $product ){
        
            $item = ( new \PayPal\Api\Item() )
                ->setName( $product->getName() )
                ->setPrice( $product->getPrice() )
                ->setCurrency( 'USD' )
                ->setQuantity( 1 );
            
            $list->addItem( $item );
        
        }

        $details = ( new \PayPal\Api\Details() )
                ->setTax($basket->getVatPrice($vatRate))
                ->setSubtotal( $basket->getPrice() );

        $amount = ( new \PayPal\Api\Amount() )
                ->setTotal( $basket->getPrice() + $basket->getVatPrice($vatRate)) // à changer 
                ->setCurrency('USD')
                ->setDetails($details);

        // transaction 
        return ( new \PayPal\Api\Transaction() )
                ->setItemList( $list )
                ->setDescription('Achat sur eAssessme')
                ->setAmount($amount)
                ->setCustom('demo-1');
        }
}