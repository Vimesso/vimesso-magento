<?php

class Vimesso_TestController extends Mage_Core_Controller_Front_Action {

    
    public function IndexAction(){
        echo 'test index';
    }
    
    public function TestAction(){        $order = Mage::getModel('sales/order')->load(306);
        $vimessos = Mage::helper('vimesso')->getVimesso($order)->notifyCustomerWhenPurchasingVimesso();
    }
    
    /**
     * test send email to the customer with link + id vimesso +QR code
     */
    public function SendEmailForCustomerAction(){
    
        $orderId = 46241; //
        
        //mage::getModel('vimesso/vimesso')->notifyCustomerForVimesso($orderId);
        
        mage::helper('vimesso')->notifyCustomerWhenPurchasingVimesso($orderId);
        /*
        $order = Mage::getModel('sales/order')->load($orderId);
        
        echo $order->getCustomerFirstName(); echo ' - '; echo $order->getCustomerLastName();
		
	echo '<br />';
        
	echo $order->getcustomer_firstname(); echo ' - '; echo $order->getcustomer_lastname();
        */
    }
}