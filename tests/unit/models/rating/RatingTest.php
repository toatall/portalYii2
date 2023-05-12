<?php
namespace tests\unit\models\rating;


use app\models\rating\RatingData;
use app\models\rating\RatingMain;

class RatingTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {        
        (new RatingMain());
        (new RatingData());        
    }

}