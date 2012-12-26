<?php

class Vimesso_Model_Vimesso extends Varien_Object {

    public function getCreationLinkForVimesso() {
        $currentStoreId = Mage::app()->getStore()->getId();
        Mage::app()->setCurrentStore($this->getStoreId());
        
        if(Mage::helper('vimesso')->getIsFrame() == "1")
            $clink = Mage::getUrl('vimesso/index/create', array('page' => base64_encode($this->getClink())));
        else
            $clink = $this->getClink();
        
        Mage::app()->setCurrentStore($currentStoreId);
        return $clink;
    }

    public function notifyCustomerWhenPurchasingVimesso()
    {
        $emailTemplate = Mage::getStoreConfig('vimesso_options/email_settings/template_vimesso_creation');
        $this->notifyCustomerForVimesso($emailTemplate);
    }
 
    public function notifyCustomerForVimesso($emailTemplate = null)
    {
        // load template for email
        $identity = Mage::getStoreConfig('vimesso_options/email_settings/sender'); // sender email (contact@vimesso ... ) ok
        
        if (is_null($emailTemplate)) {
            $emailTemplate = Mage::getStoreConfig('vimesso_options/email_settings/template'); // ok
        }

        if ($emailTemplate == '') {
            Mage::throwException('Email template is not set (system > config > Sales > Vimesso > email template)');
        }
        
        if (!$this->getCustomerEmail()) {
            Mage::throwException('Undefined customer email.');
        }

        //send email
        $translate = Mage::getSingleton('core/translate');
        $translate->setTranslateInline(false);
        $result = Mage::getModel('Core/Email_Template')
                ->sendTransactional(
                    $emailTemplate, 
                    $identity, 
                    $this->getCustomerEmail(), 
                    'name', 
                    array(
                        'vimesso_creation_link' => $this->getCreationLinkForVimesso(),
                        'vimesso_cqr' => $this->getCqr(),
                        'vimesso_code' => $this->getCode(),
                    )
                );
        $translate->setTranslateInline(true);
    }
}