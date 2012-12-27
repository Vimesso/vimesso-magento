<?php

class Vimesso_Helper_Data extends Mage_Core_Helper_Abstract {

    const VIMESSO_PRODUCT_SKU = 'vimesso-product';
    const XML_PATH_VIMESSO_ENABLED = 'vimesso_options/settings/active';
    const XML_PATH_VIMESSO_IFRAME = 'vimesso_options/display/iframe';

    protected $_vimessoProduct;

    public function getIsEnabled() {
        return Mage::getStoreConfig(self::XML_PATH_VIMESSO_ENABLED);
    }
    
    public function getIsFrame() {
        return Mage::getStoreConfig(self::XML_PATH_VIMESSO_IFRAME);
    }

    public function getMessageStatus($status) {
        switch ($status) {
            case '0':
                return $this->__('No');
                break;
            case '1':
                return $this->__('Yes');
                break;
        }
    }

    public function getOptionTitle() {
        $title = Mage::getStoreConfig('vimesso_options/display/option_title');

        if ($title == "")
            $title = $this->__('Choose Vimesso');

        return $title;
    }

    public function getOptionLabel() {
        $label = Mage::getStoreConfig('vimesso_options/display/option_label');

        if ($label == "")
            $label = $this->__('Add Vimesso');

        return $label;
    }

    public function getOptionDescription() {
        $description = Mage::getStoreConfig('vimesso_options/display/option_description');

        if ($description == "")
            $description = $this->__('Vimesso description');

        return $description;
    }

    public function setVimesso($vimesso, $order)
    {
        $vimesso->setCustomerEmail($order->getCustomerEmail());
        $vimesso->setStoreId($order->getStoreId());
        $order->setVimesso($vimesso);
        return $this;
    }
    
    public function registerVimesso($order)
    {
        if($this->getIsEnabled()) {
            if( $this->orderContainsVimesso($order) ) {
                $vimesso = Mage::helper('vimesso/api')->registerVimesso($order->getQuoteId(), $order->getCustomerEmail());
                $this->setVimesso($vimesso, $order);
                return $vimesso;
            }
        } else {
            Mage::throwException('Cannot register Vimesso, Vimesso Module is disabled.');
        }
    }
    
    /**
     * get the vimesso of current order
     * 
     */
    public function getVimesso($order)
    {
        
        if ($vimesso = $order->getVimesso()) {
            return $vimesso;
        }
        
        if( $this->orderContainsVimesso($order) ) {
            $vimesso = Mage::helper('vimesso/api')->getVimessoByOnum($order->getQuoteId());
            $this->setVimesso($vimesso, $order);
            return $vimesso;
        }
    }
    
    public function getCustomerVimessoCollection($customer)
    {
        if ($vimessoCollection = $customer->getVimessoCollection()) {
            return $vimessoCollection;
        }
        
        $vimessoCollection = Mage::helper('vimesso/api')->getVimessoCollectionByEmail($customer->getEmail());
        $customer->setVimessoCollection($vimessoCollection);
        return $vimessoCollection;
    }

    public function hasVimessoCreated($order) {
        $result = $this->getVimesso($order);

        if ($result == null) {
            return false;
        }
        else
            return true;
    }

    /**
     * Check if an order contains a vimesso
     * 
     * @param type $order 
     */
    public function orderContainsVimesso($order)
    {
        $value = false;
        foreach ($order->getAllItems() as $item)
        {
            if ($item->getSku() == $this->getVimessoSku())
                $value = true;
        }

        return $value;
    }

    public function getQuoteVimessoItem($quote)
    {
        foreach ($quote->getAllItems() as $item)
        {
            if ($item->getSku() == $this->getVimessoSku())
                return $item;
        }

        return false;
    }

    public function getVimessoSku()
    {
        return self::VIMESSO_PRODUCT_SKU;
    }
    
    public function getVimessoProduct()
    {
        if (is_null($this->_vimessoProduct)) {
            $product = Mage::getModel('catalog/product');
            $product->load($product->getIdBySku($this->getVimessoSku()));
            $this->_vimessoProduct = $product;
        }
        return $this->_vimessoProduct;
    }
}