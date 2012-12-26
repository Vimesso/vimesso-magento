<?php

class Vimesso_Block_Adminhtml_Customer_Edit_Tab_Vimesso extends Mage_Adminhtml_Block_Widget_Grid
implements Mage_Adminhtml_Block_Widget_Tab_Interface
{
   
    /**
     * Set the template for the block
     *
     */
    public function _construct()
    {
        parent::_construct(); 
    }

    public function isVimessoOnline()
    {
        return Mage::helper('vimesso/api')->pingVimesso();
    }
    
    protected function _prepareCollection()
    {
        if ($this->isVimessoOnline()) {
            $customer = Mage::registry('current_customer');
            $collection = Mage::helper('vimesso')->getCustomerVimessoCollection($customer);
            $this->setCollection($collection);
        } else {
            echo $this->__('Vimesso is offline, please try later.');
        }
        
        parent::_prepareCollection();
    }

        
    protected function _prepareColumns()
    {
        
        $this->addColumn('order_number', array(
            'header'    => Mage::helper('vimesso')->__('Order #'),
            'width'     => '100',
            'index'     => 'order_number',
        ));

        $this->addColumn('code', array(
            'header'    => Mage::helper('vimesso')->__('Vimesso #'),
            'width'     => '100',
            'index'     => 'code',
        ));

        $this->addColumn('created_on', array(
            'header'    => Mage::helper('vimesso')->__('Created On'),
            'index'     => 'created_on',
            'type'      => 'text',
        ));

        parent::_prepareColumns();
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
