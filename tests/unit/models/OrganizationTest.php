<?php

namespace tests\unit\models;

use app\models\Organization;
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

class OrganizationTest extends \Codeception\Test\Unit
{   
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
     * @see Organization::findActual()
     */
    public function testFindActual()
    {
        $dataActual = ArrayHelper::map(
            (new Query())
                ->from('{{%organization}}')
                ->where(['code_parent' => null])
                ->all(),
            'code', 'code');
        $dataTest = ArrayHelper::map(Organization::findActual()->all(), 'code', 'code');

        $this->assertEquals($dataTest, $dataActual);
    }

    /**
     * @see Organization::getDropDownList()
     */
    public function testGetDropDownList()
    {
        $defaultOption = [''=>'Все'];

        $dataTest = Organization::getDropDownList(true, true, true);        
        $dataActual = ArrayHelper::map(Organization::find()
                ->where(['<>', 'code', '8600'])
                ->andWhere(['code_parent'=>null])
                ->all(),
            'code',
            'fullName');
        $dataActual = ArrayHelper::merge($defaultOption, $dataActual);
        $this->assertEquals($dataTest, $dataActual);
    }


    /**
     * @see Organization::isRoleModerator($code)
     */
    public function testIsRoleModerator()
    {        
        SecurityHelper::login();

        // without roles     
        $this->assertFalse(Organization::isRoleModerator('8601'));
        
        // 2. role moderator
        SecurityHelper::logout();
        SecurityHelper::login();
        SecurityHelper::createRole('ModeratorOrganizationDepartment-8601', true);
        $this->assertTrue(Organization::isRoleModerator('8601'));                

        // 3. role admin
        SecurityHelper::logout();
        SecurityHelper::login();
        SecurityHelper::createRole('admin', true);
        $this->assertTrue(Organization::isRoleModerator('8601'));
    }

    private function getUploadedFiles($images)
    {
        $result = [];        
        foreach($images as $image) {
            $img = new \yii\web\UploadedFile();
            $img->tempName = codecept_data_dir() . 'filesForUpload\\' . $image;
            $img->name = basename($img->tempName);            
            $result[] = $img;
        }
        return $result;
    }

    /**
     * @see Organization::upload()
     * @see Organization::afterDelete()
     * @see Organization::deleteImages
     */
    public function testDeleteUploaded()
    {
        $images = ['Desert.jpg', 'Jellyfish.jpg'];

        $org = new Organization();
        $org->code = '1234';
        $org->name = 'Test organization';
        $org->date_create = Yii::$app->formatter->asDatetime('now');
        $org->date_edit = Yii::$app->formatter->asDatetime('now');
        $org->uploadImages = $this->getUploadedFiles($images);
        $org->sort = 0;
        $org->save();       

        // каталог загрузки
        $pathUpload = ReflectionHelper::invokePrivateMethod($org, 'getPathUploadImages');
        $org->upload();
               
        // проверка загрузки изображений
        foreach($images as $image) {           
            $this->assertFileExists(Yii::getAlias('@webroot') . $pathUpload . $image);
        }
        
        // удаление 1 фотографии
        $org->deleteImages = [$pathUpload . $images[0]];
        $org->save();
        $this->assertFileNotExists(Yii::getAlias('@webroot') . $pathUpload . $images[0]);

        // удаление организации
        $org->delete();
        foreach($images as $image) {           
            $this->assertFileNotExists(Yii::getAlias('@webroot') . $pathUpload . $image);
        }        
        
        // удаление файлов
        $this->deleteFilesAfterTest(Yii::getAlias('@webroot') . $pathUpload);
    }


    /**
     * Очистка 
     * @param string $path
     * @return bool
     */
    private function deleteFilesAfterTest($path)
    {
        return FileHelper::unlink($path);
    }
   

}
