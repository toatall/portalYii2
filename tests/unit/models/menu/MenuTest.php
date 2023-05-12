<?php
namespace tests\unit\models\menu;

use app\models\menu\Menu;
use app\models\menu\MenuBuilder;
use app\models\menu\MenuDepartments;
use app\models\menu\MenuNewsOrganizations;

class MenuTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {        
        (new Menu());
        (new MenuBuilder());
        (new MenuDepartments());        
        (new MenuNewsOrganizations());
    }

}