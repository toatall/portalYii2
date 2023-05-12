<?php
namespace tests\unit\models\migrantsquestionnation;

use app\models\MigrantsQuestionnation;
use app\models\MigrantsQuestionnationSearch;

class MigrantsQuestionnationTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {
        (new MigrantsQuestionnation());
        (new MigrantsQuestionnationSearch());
    }

}