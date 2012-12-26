<?php

class Vimesso_Block_Adminhtml_Popup_Reading extends Mage_Adminhtml_Block_Template
{
   
    public function __construct() {
        parent::__construct();

    }
    
    
    public function getPopupUrl($url) {
        return $url;

    }
    
}
