<?php

class Vimesso_Block_Customer_List extends Mage_Core_Block_Template
{
    protected $_customer;
    
    public function setCustomer($customer)
    {
        $this->_customer = $customer;
        return $this;
    }

    public function isVimessoOnline()
    {
        return Mage::helper('vimesso/api')->pingVimesso();
    }
    
    public function getVimessoCollection()
    {
        return Mage::helper('vimesso')->getCustomerVimessoCollection($this->_customer);
    }    
}