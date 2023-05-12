<?php
namespace tests\unit\modules\admin;

use app\modules\admin\models\FooterData;
use app\modules\admin\models\FooterType;

class FooterTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {
        (new FooterData()); 
        (new FooterType());       
    }

}