<?php
namespace tests\unit\models\telephone;


use app\models\zg\EmailGoverment;
use app\models\zg\EmailGovermentSearch;
use app\models\zg\ZgTemplate;
use app\models\zg\ZgTemplateFile;

class ZgTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {        
        (new EmailGoverment());
        (new EmailGovermentSearch());        
        (new ZgTemplate());
        (new ZgTemplateFile());
    }

}