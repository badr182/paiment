<?php

namespace Paiement ;

class Basket
{

    private $products;

    // generer un faux panier 
    public static function fake(){
        $products = array_map( function($price){
            return (new Product())
             ->setPrice($price)
             ->setName('Product qui coute '.$price);
        },
        [1.21,10.22,40.00]);
        return (new self())
        ->setProducts($products);
    }

    public function getPrice():float
    {
        return array_reduce( $this->getProducts(),function( $total,Product $product ){
            return $product->getPrice() + $total ;
        });
    }


    /**
     * Get the value of products
     */ 
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Set the value of products
     *
     * @return  self
     */ 
    public function setProducts($products)
    {
        $this->products = $products;

        return $this;
    }
}
