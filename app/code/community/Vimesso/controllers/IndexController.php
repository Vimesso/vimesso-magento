<?php

class Vimesso_IndexController extends Mage_Core_Controller_Front_Action
{
    
    public function createAction()
    {
        
        $this->loadLayout();
        $this->getLayout();
        
        $url = base64_decode($this->getRequest()->getParam('page'));
        
        $this->getLayout()->getBlock('vimesso.iframe')->setIframeUrl($url);
        
        $this->renderLayout();
    }
    
    public function viewAction()
    {
        
        $this->loadLayout();
        $this->getLayout();
        
        $url = base64_decode($this->getRequest()->getParam('page'));
        
        $this->getLayout()->getBlock('vimesso.iframe')->setIframeUrl($url);
        
        $this->renderLayout();
    }
    
}