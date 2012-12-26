<?php

class Vimesso_Block_Adminhtml_Customer_Renderer_Link extends Mage_Adminhtml_Block_Widget_Grid_Column_Renderer_Abstract
{

    public function render(Varien_Object $row)
    {
        $value =  $row->getClink();
        //return '<span style="color:red;">'.$value.'</span>';
        //return '<a href="'.$value.'">Create</a>';
    }
}