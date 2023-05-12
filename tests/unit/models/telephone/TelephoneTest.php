<?php
namespace tests\unit\models\telephone;


use app\models\telephone\TelephoneDepartment;
use app\models\telephone\TelephoneSearch;
use app\models\telephone\TelephoneSOAP;
use app\models\telephone\TelephoneUser;

class TelephoneTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {        
        (new TelephoneDepartment());
        (new TelephoneSearch());        
        (new TelephoneSOAP());
        (new TelephoneUser());
    }

}