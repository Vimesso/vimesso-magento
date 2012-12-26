<?php

$installer = $this;
$installer->startSetup();

/* vimesso product sku */
$vimessoSku = Mage::helper('vimesso')->getVimessoSku();

/* get all website ids */
$websites = Mage::app()->getWebsites();
$websiteIds = array();
foreach($websites as $website) {
    $websiteIds[] = $website->getId();
}

/* remove current vimesso product if exists */
$oldProduct = Mage::getModel('catalog/product');
$oldProduct->load($oldProduct->getIdBySku($vimessoSku));
if ($oldProduct->getId()) {
    $oldProduct->delete();
}

/* create vimesso product */
$product = Mage::getModel('catalog/product');
$product->setSku($vimessoSku);
$product->setPrice(0);
$product->setWebsiteIds($websiteIds);
$product->setAttributeSetId($product->getDefaultAttributeSetId()); 
$product->setTypeId(Mage_Catalog_Model_Product_Type::TYPE_VIRTUAL);
$product->setName('Vimesso');
$product->setStatus(1);		
$product->setVisibility(Mage_Catalog_Model_Product_Visibility::VISIBILITY_NOT_VISIBLE);
$product->setCreatedAt(strtotime('now'));
$product->save();

/* create stock item for vimesso product */
$stockItem = Mage::getModel('cataloginventory/stock_item');
$stockItem->addData($product->getStockData())
                      ->setProduct($product)
                      ->setProductId($product->getId())
                      ->setStockId($stockItem->getStockId());
$stockItem->setQty(1);
$stockItem->setIsInStock(1);
$stockItem->save();
$product->setStockItem($stockItem);
$product->save();

$installer->endSetup();

?>