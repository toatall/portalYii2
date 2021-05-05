<?php
namespace app\widgets;

/**
 * @author toatall
 */
class DropdownLeftMenu extends \yii\bootstrap\Dropdown
{   
    public function init() 
    {
        $this->submenuOptions = ['class'=>'dropdown-menu'];
        return parent::init();
    }    
}
