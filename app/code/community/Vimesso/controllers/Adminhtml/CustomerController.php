<?php
/**
 *
 * @category    Lingueo
 * @package     Lingueo_Training
 * @copyright   Copyright (c) 2011 Lingueo
 * @license     http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

include_once "Mage/Adminhtml/controllers/CustomerController.php";

/**
 * Catalog product controller
 *
 * @category   Lingueo
 * @package    Lingueo_Training
 * @author     Lingueo Team
 */
class Vimesso_Adminhtml_CustomerController extends Mage_Adminhtml_CustomerController
{
    
    public function vimessoAction()
    {
        
    }
    
   public function printPDF(){
        
        // get order id
        $orderId = getRequest()->getParam('order_id');

        //create PDF
        $pdfModel = Mage::getModel('Vimesso/pdf_vimesso');
        $pdf = $pdfModel->getPdf($orderId);
        $this->_prepareDownloadResponse(mage::helper('vimesso')->__('vimesso pdf') . '.pdf', $pdf->render(), 'application/pdf');
   }
    
}