<?php

class Vimesso_CustomerController extends Mage_Core_Controller_Front_Action
{
    
    protected function _getSession()
    {
        return Mage::getSingleton('customer/session');
    }

    public function preDispatch()
    {

        parent::preDispatch();

        if (!$this->getRequest()->isDispatched()) {
            return;
        }

        $action = $this->getRequest()->getActionName();
        if (!preg_match('/^(create|login|logoutSuccess|forgotpassword|forgotpasswordpost|confirm|confirmation)/i', $action)) {
            if (!$this->_getSession()->authenticate($this)) {
                $this->setFlag('', 'no-dispatch', true);
            }
        } else {
            $this->_getSession()->setNoReferer(true);
        }
    }
    
    public function listAction()
    {
        
        $this->loadLayout();
        

        $customerId = Mage::getSingleton('customer/session')->getCustomerId();
        $customer = Mage::getModel('customer/customer')->load($customerId);
        $this->getLayout()->getBlock('vimesso.customer.list')->setCustomer($customer);
        
        $this->renderLayout();
    }

    /*
     *  send email to notify customer of his creation
     */
    public function sendCustomerConfirmationEmailAction(){
        
        
        // afaire
        
        
    }
    
}