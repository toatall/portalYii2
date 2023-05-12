<?php
namespace tests\unit\models\news;

use app\models\news\News;
use app\models\news\NewsComment;
use app\models\news\NewsQuery;
use app\models\news\NewsSearch;

class NewsTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {        
        (new News());
        (new NewsQuery([]));
        (new NewsComment());        
        (new NewsSearch());
    }

}