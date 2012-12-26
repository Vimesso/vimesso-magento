<?php

class Vimesso_Block_Adminhtml_Sales_Edit_Tab_Vimesso extends Mage_Adminhtml_Block_Template
implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
    public function orderContainsVimesso()
    {   
        $order = Mage::registry('current_order');
        return Mage::helper('vimesso')->orderContainsVimesso($order);
    }
    
    public function isVimessoOnline()
    {
        return Mage::helper('vimesso/api')->pingVimesso();
    }
   
    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct(); 
        $this->setTemplate('vimesso/sales/vimesso.phtml');
    }
    
    public function getVimesso()
    {
        $order = Mage::registry('current_order');
        $vimesso = Mage::helper('vimesso')->getVimesso($order);
        return $vimesso;
    }

        /**
     * Retrieve the label used for the tab relating to this block
     *
     * @return string
     */
    public function getTabLabel()
    {
        return $this->__('Vimesso');
    }
 
    /**
     * Retrieve the title used by this tab
     *
     * @return string
     */
    public function getTabTitle()
    {
        return $this->__('Click here to view Vimesso tab content');
    }
 
    /**
     * Determines whether to display the tab
     * Add logic here to decide whether you want the tab to display
     *
     * @return bool
     */
    public function canShowTab()
    {
        return true;
    }
 
    /**
     * Stops the tab being hidden
     *
     * @return bool
     */
    public function isHidden()
    {
        return false;
    }
 
    
     /**
     * call order controller to generate PDF file 
     */
    public function getPrintUrl(){
        return $this->getUrl('vimesso/Adminhtml_Order/printPdf', array('order_id' => $this->getRequest()->getParam('order_id')));
    }
 
    /**
     * call order controller to notify customer
     */
    public function getNotifyUrl(){
        return $this->getUrl('vimesso/Adminhtml_Order/notifyCustomer', array('order_id' => $this->getRequest()->getParam('order_id')));
    }
    
    /**
     * get url to the controller to open a popup for reading vimesso 
     */
    public function getUrlForPopupReading(){

        return $this->getUrl('vimesso/Adminhtml_Order/OpenPopupReader');
    }
    
    
    /**
     * AJAX TAB's
     * If you want to use an AJAX tab, uncomment the following functions
     * Please note that you will need to setup a controller to recieve
     * the tab content request
     *
     */
    /**
     * Retrieve the class name of the tab
     * Return 'ajax' here if you want the tab to be loaded via Ajax
     *
     * return string
     */
#   public function getTabClass()
#   {
#       return 'my-custom-tab';
#   }
 
    /**
     * Determine whether to generate content on load or via AJAX
     * If true, the tab's content won't be loaded until the tab is clicked
     * You will need to setup a controller to handle the tab request
     *
     * @return bool
     */
#   public function getSkipGenerateContent()
#   {
#       return false;
#   }
 
    /**
     * Retrieve the URL used to load the tab content
     * Return the URL here used to load the content by Ajax
     * see self::getSkipGenerateContent & self::getTabClass
     *
     * @return string
     */
#   public function getTabUrl()
#   {
#       return null;
#   }
    
}
