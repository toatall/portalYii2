<?php

namespace tests\unit\models;

use app\models\Organization;
use app\models\Protocol;
use app\tests\fixtures\RoleFixture;
use app\tests\fixtures\UserFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\unit\helpers\SecurityHelper;
use Codeception\Util\ReflectionHelper;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use Faker;
use yii\helpers\FileHelper;

class ProtocolTest extends \Codeception\Test\Unit
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
            RoleFixture::class,            
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
                
        Yii::setAlias('@webroot', codecept_root_dir('tests\\') . 'web');
    }

    /**
     * {@inheritdoc}
     */
    protected function _after()
    {
        FileHelper::removeDirectory(Yii::getAlias('@webroot'));
    }

    
    /**
     * General test
     */
    public function testProtocol()
    {
        $files =  ['Desert.jpg', 'Example.rar', 'Example.xlsx', 'Jellyfish.jpg'];
        
        $model = $this->generateModel();
        $model->uploadMainFiles = [
            $this->getUploadedFiles($files[0]),
            $this->getUploadedFiles($files[1]),
        ];
        $model->uploadExecuteFiles = [
            $this->getUploadedFiles($files[2]),
            $this->getUploadedFiles($files[3]),
        ];
        // сохранение
        $this->assertTrue($model->save());
        $model->uploadFiles();

        // проверка загруженных файлов
        $id = $model->id;
        $pathUploadMain = ReflectionHelper::invokePrivateMethod($model, 'getPathMainFiles');
        $pathUploadExecute = ReflectionHelper::invokePrivateMethod($model, 'getPathExecuteFiles');
        $this->assertFileExists(Yii::getAlias('@webroot') . $pathUploadMain . $files[0]);
        $this->assertFileExists(Yii::getAlias('@webroot') . $pathUploadMain . $files[1]);
        $this->assertFileExists(Yii::getAlias('@webroot') . $pathUploadExecute . $files[2]);
        $this->assertFileExists(Yii::getAlias('@webroot') . $pathUploadExecute . $files[3]);

        // удаление файлов
        $model->deleteMainFiles = [$files[0]];
        $model->deleteExecuteFiles = [$files[2]];
        $this->assertTrue($model->save());
        $this->assertFileNotExists(Yii::getAlias('@webroot') . $pathUploadMain . $files[0]);
        $this->assertFileNotExists(Yii::getAlias('@webroot') . $pathUploadExecute . $files[2]);

        // полное удаление 
        $this->assertNull(ReflectionHelper::invokePrivateMethod($model, 'deleteFiles', [null, null]));
        $this->assertTrue($model->delete() !== false);
        $this->assertFileNotExists(Yii::getAlias('@webroot') . $pathUploadMain . $files[0]);
        $this->assertFileNotExists(Yii::getAlias('@webroot') . $pathUploadMain . $files[1]);
        $this->assertFileNotExists(Yii::getAlias('@webroot') . $pathUploadExecute . $files[2]);
        $this->assertFileNotExists(Yii::getAlias('@webroot') . $pathUploadExecute . $files[3]);
        $query = (new Query())
            ->from(Protocol::tableName())
            ->where(['id' => $id])
            ->exists();
        $this->assertFalse($query);
    }

    /**
     * Find and check roles
     */
    public function testFindAndRoles()
    {
        $date = '2022-01-01 01:02:03';
        
        $model = $this->generateModel();
        $model->date = $date;
        $model->afterFind();
        $this->assertEquals($model->date, Yii::$app->formatter->asDate($date));

        $this->assertIsString(Protocol::roleModerator());

        $this->assertFalse(Protocol::isRoleModerator());

        SecurityHelper::logout();
        SecurityHelper::login();
        SecurityHelper::assignRole('admin');
        $this->assertTrue(Protocol::isRoleModerator());
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


    /**
     * @return Protocol
     */
    private function generateModel()
    {
        return new Protocol([
            'type_protocol' => $this->faker->name,
            'date' => \Yii::$app->formatter->asDate($this->faker->dateTime),
            'number' => $this->faker->numerify,
            'name' => $this->faker->name,
            'executor' => $this->faker->lastName . ' ' . $this->faker->firstNameMale,
            'execute_description' => $this->faker->text(200),            
        ]);
    }

    /**
     * Get UploadedFile model
     */
    private function getUploadedFiles($image)
    {        
        $img = new \yii\web\UploadedFile();
        $img->tempName = codecept_data_dir() . 'filesForUpload\\' . $image;
        $img->name = basename($img->tempName);                
        return $img;
    }
   

}
