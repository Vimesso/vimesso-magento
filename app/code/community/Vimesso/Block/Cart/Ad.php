<?php

class Vimesso_Block_Cart_Ad extends Mage_Catalog_Block_Product_Abstract
{
    
    public function __construct()
    {
        parent::__construct();
        
    }
    
    protected function _prepareLayout()
    {
        parent::_prepareLayout();
        
        if(Mage::getStoreConfig('vimesso_options/settings/active') == "1")
        {
            $this->setTemplate('vimesso/ad.phtml');
            $this->setHtmlContent($this->getLayout()->createBlock('cms/block')->setBlockId('vimesso-cart-block')->toHtml());
        }
        
    }

}