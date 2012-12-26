<?php

/**
 *
 * @category    Lingueo
 * @package     Lingueo_Training
 * @copyright   Copyright (c) 2011 Lingueo
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Catalog product controller
 *
 * @category   Lingueo
 * @package    Lingueo_Training
 * @author     Lingueo Team
 */
class Vimesso_Adminhtml_OrderController extends Mage_Adminhtml_Controller_Action {
    
    /**
     * generate PDF with qr code, vimesso id for customer
     */
    public function printPdfAction() {

        // get order id
        $orderId = $this->getRequest()->getParam('order_id');
        $order = Mage::getModel('sales/order')->load($orderId);

        try {
            //create PDF
            $pdfModel = Mage::getModel('vimesso/PdfVimesso');
            $pdf = $pdfModel->getPdf($order);
            
            $this->_prepareDownloadResponse(mage::helper('vimesso')->__('vimesso pdf') . '.pdf', $pdf->render(), 'application/pdf');
            
        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
            $this->_redirect('adminhtml/sales_order/view', array('order_id' => $orderId));
        }
    }

    
    /**
     * thanks and notify customer to ask him to create the vimesso 
     */
    public function notifyCustomerAction() {
        
        // get order id
        $orderId = $this->getRequest()->getParam('order_id');

        try {
            //get order infos
            $order = Mage::getModel('sales/order')->load($orderId);

            // call helper to send email with informations
            Mage::helper('vimesso')->getVimesso($order)->notifyCustomerForVimesso();
            Mage::getSingleton('adminhtml/session')->addSuccess(Mage::helper('vimesso')->__('Mail has been sent.'));
            $this->_redirect('adminhtml/sales_order/view', array('order_id'=>$orderId));
            
        } catch (Exception $ex) {
            Mage::getSingleton('adminhtml/session')->addError($ex->getMessage());
            $this->_redirect('adminhtml/system_config/edit', array('section' => 'sales'));
        }
    } 
    
}