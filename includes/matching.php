<?php

/**
 * Created by PhpStorm.
 * User: david
 * Date: 31/10/2017
 * Time: 21:48
 */
class matching
{
   public $data = array(
       "affiliates-jigoshop-light"=>"https://wordpress.org/plugins/yith-woocommerce-affiliates",
       "jigoshop-basic-bundle-shipping"=>"https://wordpress.org/plugins/woo-product-bundle/",
       "clickdesk-live-support-chat-plugin"=>"https://fr.wordpress.org/plugins/live-chats-for-woocommerce-all-in-one/",
       "credimax-payment-gateway"=>"https://fr.wordpress.org/plugins/woocommerce-payment-gateway/",
       "test"=>"https://fr.wordpress.org/plugins/woocommerce-payment-gateway/",
   ) ;

   function getData($key){
       return $this->data[$key] ;
   }

}