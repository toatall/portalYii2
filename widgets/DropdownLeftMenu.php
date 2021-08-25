<?php
namespace app\widgets;

/**
 * @author toatall
 */
class DropdownLeftMenu extends \yii\bootstrap4\Dropdown
{   
    public function init() 
    {
        $this->submenuOptions = ['class'=>'dropdown-menu'];
        return parent::init();
    }    
}
