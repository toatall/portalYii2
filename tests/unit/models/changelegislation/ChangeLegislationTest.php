<?php
namespace tests\unit\models\changelegislation;

use app\models\ChangeLegislation;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use app\tests\unit\helpers\SecurityHelper;
use Faker;
use Yii;
use yii\db\Query;

class ChangeLegislationTest extends \Codeception\Test\Unit
{

    /**
     * @var Faker\Generator
     */
    private $faker; 

    /**
     * {@inheritdoc}
     */
    public function _fixtures()
    {        
        return [            
            UserFixture::class,               
            OrganizationFixture::class,           
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function _before()
    {
        parent::_before();
        $this->faker = Faker\Factory::create();   

        // залогинить пользователя        
        SecurityHelper::login();                
    }

    /**
     * @return ChangeLegislation
     */
    private function generateModel()
    {
        $facker = $this->faker;
        return new ChangeLegislation([
            'type_doc' => $facker->text(100),
            'is_anti_crisis' => rand(0, 1),
            'date_doc' => \Yii::$app->formatter->asDate($this->faker->dateTime),
            'number_doc' => $facker->numerify(),
            'name' => $facker->title(),
            'date_doc_1' => \Yii::$app->formatter->asDate($this->faker->dateTime),
            'date_doc_2' => \Yii::$app->formatter->asDate($this->faker->dateTime),
            'date_doc_3' => \Yii::$app->formatter->asDate($this->faker->dateTime),
            'status_doc' => $facker->text(50),
            'text' => $facker->text(3000),                        
        ]);
    }

    /**
     * Check user's model
     */
    public function testModelUser()
    {
        $model = $this->generateModel();
        $model->save();
        $this->assertNotNull($modelAuthor = $model->getAuthorModel()->one());
        $this->assertEquals($model->author, $modelAuthor->username);
    }

    public function testAfterFind()
    {   
        // full     
        $model = $this->generateModel();
        $d = $model->date_doc;
        $d1 = $model->date_doc_1;
        $d2 = $model->date_doc_2;
        $d3 = $model->date_doc_3;
        $model->afterFind();
        $this->assertEquals($model->date_doc, $this->formatAsDate($d));
        $this->assertEquals($model->date_doc_1, $this->formatAsDate($d1));
        $this->assertEquals($model->date_doc_2, $this->formatAsDate($d2));
        $this->assertEquals($model->date_doc_3, $this->formatAsDate($d3));

        // null
        $model->date_doc = null;
        $model->date_doc_1 = null;
        $model->date_doc_2 = null;
        $model->date_doc_3 = null;
        $model->afterFind();
        $this->assertNull($model->date_doc);
        $this->assertNull($model->date_doc_1);
        $this->assertNull($model->date_doc_2);
        $this->assertNull($model->date_doc_3);
    }    

    private function formatAsDate($date)
    {
        return Yii::$app->formatter->asDate($date);
    }


    public function testRoleModerator()
    {
        $role = ChangeLegislation::roleModerator();
        $this->assertNotEmpty($role);
       
        $this->assertFalse(ChangeLegislation::isRoleModerator());

        SecurityHelper::createRole($role, true);
        SecurityHelper::relogin();        
       
        $this->assertTrue(ChangeLegislation::isRoleModerator());

        SecurityHelper::revokeRoles();
        SecurityHelper::assignRole('admin');
        SecurityHelper::relogin();
        $this->assertTrue(ChangeLegislation::isRoleModerator());
    }
    
    /**
     * General test
     */
    public function testGeneral()
    {        
        $model = $this->generateModel();
        
        // сохранение
        $this->assertTrue($model->save());
        $id = $model->id;
        
       
        // удаление         
        $this->assertTrue($model->delete() !== false);
        
        $query = (new Query())
            ->from(ChangeLegislation::tableName())
            ->where(['id' => $id])
            ->exists();
        $this->assertFalse($query);
    }
    

}