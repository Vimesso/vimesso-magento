<?php

class Vimesso_Model_Observer {

    public function applyVimesso(Varien_Event_Observer $observer) {
        $event = $observer->getEvent();
        $request = $event->getRequest();
        
        try {
            $cart = Mage::getSingleton('checkout/cart');
            $vimessoItem = Mage::helper('vimesso')->getQuoteVimessoItem($cart->getQuote());
            $allowVimesso = $request->getPost('allow_vimesso') == "1";
            if (!$vimessoItem && $allowVimesso) {
                $product = Mage::helper('vimesso')->getVimessoProduct();
                $cart->addProduct($product);
                $cart->save();
                Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
            } elseif ($vimessoItem && !$allowVimesso) {
                $cart->removeItem($vimessoItem->getId());
                $cart->save();
                Mage::getSingleton('checkout/session')->setCartWasUpdated(true);
            }
        } catch (Exception $e) {
            Mage::log($e->getMessage());
        }
    }

    public function salesOrderAfterPlace(Varien_Event_Observer $observer)
    {
        $order = $observer->getEvent()->getOrder();
        if (Mage::helper('vimesso')->orderContainsVimesso($order)) {
            try {
                Mage::helper('vimesso')->registerVimesso($order);
            } catch (Exception $e) {
                Mage::logException($e);
                Mage::getSingleton('checkout/cart')->getCheckoutSession()->setGotoSection('shipping_method');
                Mage::throwException('There is problem with connection to Vimesso. Please remove Vimesso or try later.');                
            }
        }
        return;
    }

    public function salesQuoteSubmitSuccess(Varien_Event_Observer $observer) {
        $order = $observer->getEvent()->getOrder();
        $vimesso = Mage::helper('vimesso')->getVimesso($order);

        // check configuration for sending email
        if($vimesso && Mage::getStoreConfig('vimesso_options/email_settings/enable_vimesso_creation') ) {
            $vimesso->notifyCustomerWhenPurchasingVimesso();
        }
            
        return;
    }

}
