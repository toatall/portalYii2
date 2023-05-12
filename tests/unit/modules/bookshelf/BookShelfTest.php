<?php
namespace tests\unit\modules\bookshelf;

use app\modules\bookshelf\models\BookShelf;

class BookShelfTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {
        (new BookShelf()); 
    }

}