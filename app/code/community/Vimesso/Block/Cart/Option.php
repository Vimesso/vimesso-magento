<?php

class Vimesso_Block_Cart_Option extends Mage_Checkout_Block_Cart_Abstract
{
    protected $_isVimessoOnline;

    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        if(Mage::getStoreConfig('vimesso_options/settings/active') == "1")
        {
            $this->setTemplate('vimesso/option.phtml');
            $product = Mage::helper('vimesso')->getVimessoProduct();
            
            $this->setPrice($product->getPrice());
        }
    }
    
    public function isVimessoOnline()
    {
        if (is_null($this->_isVimessoOnline)) {
            $this->_isVimessoOnline = Mage::helper('vimesso/api')->pingVimesso();
        }
        
        return $this->_isVimessoOnline;
    }

    public function quoteContainsVimesso()
    {
        $quote = $this->getQuote();
        $result = Mage::helper('vimesso')->getQuoteVimessoItem($quote);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}