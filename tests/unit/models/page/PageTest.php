<?php
namespace tests\unit\models\page;


use app\models\page\Page;
use app\models\page\PageSearch;

class PageTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {        
        (new Page());
        (new PageSearch());        
    }

}