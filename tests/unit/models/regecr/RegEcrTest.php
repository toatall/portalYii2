<?php
namespace tests\unit\models\regecr;


use app\models\regecr\RegEcr;
use app\models\regecr\RegEcrSearch;

class RegEcrTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {        
        (new RegEcr());
        (new RegEcrSearch());        
    }

}