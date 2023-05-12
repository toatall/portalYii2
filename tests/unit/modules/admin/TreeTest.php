<?php
namespace tests\unit\modules\admin;

use app\modules\admin\models\tree\Tree;
use app\modules\admin\models\tree\TreeAccess;
use app\modules\admin\models\tree\TreeBuild;

class TreeTest extends \Codeception\Test\Unit
{       

    public function testTest()
    {
        (new Tree());
        (new TreeAccess());
        (new TreeBuild());   
    }

}