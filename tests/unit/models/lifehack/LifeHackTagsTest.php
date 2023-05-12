<?php
namespace tests\unit\models\lifehack;

use app\models\lifehack\Lifehack;
use app\models\lifehack\LifehackLike;
use app\models\lifehack\LifehackTags;
use app\models\User;
use app\tests\fixtures\LifeHackFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use app\tests\unit\helpers\ReflectionHelper;
use app\tests\unit\helpers\SecurityHelper;
use Faker;
use Yii;
use yii\helpers\FileHelper;

class LifeHackTagsTest extends \Codeception\Test\Unit
{       

    /**
     * @var Faker\Generator
     */
    private $faker; 

    /**
     * @var \UnitTester
     */
    public $tester;    

    public function _fixtures()
    {        
        return [         
            'users' => UserFixture::class,               
            OrganizationFixture::class,
            'lifehacks' => LifeHackFixture::class,         
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function _before()
    {
        $this->faker = Faker\Factory::create();           
    }   

    public function testBasic()
    {
        $model = new LifehackTags();
        expect($model->attributeLabels())->toBeArray();
    }


    public function testTags()
    {
        $tags = ['#tag', '#new', '#excel', '#word'];
        $this->generateTags($tags);
        $dropDown = array_values(LifehackTags::getDropDownList());
        expect($tags)->toEqual($dropDown);
    }

    /**
     * Генерирование тегов
     * @return LifehackTags[]
     */
    private function generateTags($tags)
    {
        SecurityHelper::login();
        $result = [];
        
        foreach($tags as $tag) {
            $model = new LifehackTags([
                'tag' => $tag,
            ]); 
            expect($model->save())->toBeTrue('Сохранение тэга');
        }
        return $result;
    }    
    

}