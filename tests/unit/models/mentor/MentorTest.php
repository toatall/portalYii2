<?php
namespace tests\unit\models\mentor;


use app\models\mentor\MentorPost;
use app\models\mentor\MentorPostFiles;
use app\models\mentor\MentorWays;

class MentorTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {        
        (new MentorPost());
        (new MentorPostFiles());
        (new MentorWays());        
    }

}