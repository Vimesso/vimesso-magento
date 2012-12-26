<?php

class Vimesso_Model_PdfVimesso extends Vimesso_Model_PdfHelper {

    public function getPdf($orderIds = array()) {

        // init 
        $this->_beforeGetPdf();
        $this->_initRenderer('invoice');
        $charset = 'UTF-8';
        $LineHeight = 15;
        $GrayScale = 0.2; // color
        if ($this->pdf == null) {
            $this->pdf = new Zend_Pdf();
        } else {
            $this->firstPageIndex = count($this->pdf->pages);
        }

        // get vimesso
        $vimesso = Mage::helper('vimesso')->getVimessoInformations($orderIds[0]);

        // get store id
        $storeId = Mage::app()->getStore()->getId();

        //add new page 
        $page = $this->NewPage(); // format LETTER US !!! landscape!!! '792:612:'
        $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA_BOLD), 10);


        // insert logo from store
        $imagePath = Mage::getStoreConfig('sales/identity/logo', $storeId);
        if ($imagePath) {
            $imagePath = Mage::getStoreConfig('system/filesystem/media') . '/sales/store/logo/' . $imagePath;
            if (is_file($imagePath)) {
                $imageSize = list($width, $height, $type, $attr) = getimagesize($imagePath);
                $image = Zend_Pdf_Image::imageWithPath($imagePath);
                $this->x = 25; // set x 1
                $this->y -= 100; // set y2
                $page->drawImage($image, $this->x, $this->y, $this->x + $imageSize[0], $this->y + $imageSize[1]); // to do : automatic size ( 25, 785, 25 )
            }
        } else
            die('error non image set go to : system > configuration > sales > invoice & packing slip details > logo for PDF ');

        $this->y -= $this->_LOGO_HAUTEUR + 36; // move the current position
        // insert date
        $txt = mage::helper('vimesso')->__("%s \n", date('Y-m-d'));
        //$page->drawText($txt, 15, $this->y, $charset);        //$offset = $this->DrawMultilineText($page, $name, 300, $this->y, 10, 0.2, 11);
        //$this->y -= 15;
        // insert text
        $txt .= "Hello, here the informations of your vimesso : \n";

        // insert vimesso id
        //$txt .= sprintf("Vimesso id : %s\n",$vimesso["vimessoId"]);
        // insert vimessoLink 
        $txt .= sprintf("Vimesso link : %s\n", $vimesso["vimessoLink"]);

        // text qr code
        $txt .= sprintf("Vimesso QR Code : \n");

        // 13= font size of text area
        $this->x -= 100;
        $totalLineHeight = $this->DrawMultilineText($page, $txt, 100 + $this->x, $this->y, 13, $GrayScale, $LineHeight); // $offset = $this->DrawMultilineText($page, $caption, 110, $this->y, 10, 0.2, 11);
        $this->y -= $totalLineHeight + 50;


        // draw PC
        $imagePc = Zend_Pdf_Image::imageWithPath(Mage::getBaseDir().DS.'media'.DS.'vimesso'.DS.'Louboutin-carton-v2-PC.jpg');
        $page->drawImage($imagePc, 500, 300, 700, 500  );
         
        // draw phone
        
        
        // get zend image from remote URl and put them into media/vimesso folder
        $qRCodeImage = $this->getQrCodeImage($vimesso["vimessoId"], $vimesso["VimessoQrCodeUrl"]);

        
        
        // get path of qr image
        $QrCodeImagePath = Mage::getBaseDir() . DS . 'media' . DS . 'vimesso' . DS . $vimesso["vimessoId"].'.png';
        
        $QrCodeImageSize = list($width, $height, $type, $attr) = getimagesize($QrCodeImagePath);
        // draw QR image        
        $page->drawImage($qRCodeImage, 792/2, 100, (792/2)+90, 190  ); //automatic size $QrCodeImageSize[0], $QrCodeImageSize[1]

        


        $this->_afterGetPdf();
        return $this->pdf;
    }

    /*
     * get image from distant web site and generate them with zend
     */

    public function getQrCodeImage($vimessoId, $url) {

        
        // get QR code image form vimesso web site
        $content = file_get_contents($url); // image 200 * 200 type PNG

        $baseDir = Mage::getBaseDir() . DS . 'media' . DS . 'vimesso';
        
        
        // create directory vimesso
        if (!is_dir($baseDir))
            mkdir($baseDir);

        $filePath = $baseDir . DS . $vimessoId . '.png';

        // create QR code image with url content
        $handle = fopen($filePath, 'a');
        fwrite($handle, $content);
        fclose($handle);

        // return zend image     
        return Zend_Pdf_Image::imageWithPath($filePath);
    }

}