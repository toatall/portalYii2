<?php
namespace tests\unit\models\department;

use app\models\department\Department;
use app\models\department\DepartmentCard;

class DepartmentTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {        
        (new Department());
        (new DepartmentCard());
    }

}