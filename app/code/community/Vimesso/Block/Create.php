<?php

class Vimesso_Block_Create extends Mage_Core_Block_Template
{
    protected $_clink;
    
    public function getClink()
    {
        if (is_null($this->_clink)) {
            $lastOrderId = Mage::getSingleton('checkout/session')->getLastOrderId();
            $order = Mage::getSingleton('sales/order');
            $order->load($lastOrderId);
            $vimesso = Mage::helper('vimesso')->getVimesso($order);
            
            if ($vimesso) {
                
                if(Mage::helper('vimesso')->getIsFrame() == "1")
                    $this->_clink = Mage::getUrl('vimesso/index/create', array('page' => base64_encode($vimesso->getClink())));
                else
                    $this->_clink = $vimesso->getClink();
                    
            } else {
                $this->_clink = false;
            }
        }
        
        return $this->_clink;
    }
}