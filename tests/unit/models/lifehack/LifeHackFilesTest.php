<?php
namespace tests\unit\models\lifehack;

use app\models\lifehack\Lifehack;
use app\tests\fixtures\LifeHackFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use app\tests\unit\helpers\ReflectionHelper;
use Faker;
use Yii;
use yii\helpers\FileHelper;

class LifeHackFilesTest extends \Codeception\Test\Unit
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
            UserFixture::class,               
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
     * @return Lifehack
     */
    private function generateNewModel()
    {
        return new Lifehack([
            'org_code' => $this->faker->randomElement(['8600', '8601', '8602', '8611']),
            'tags' => '#new/#superNewTag',
            'title' => $this->faker->slug(),
            'text' => $this->faker->text(1000),
            'author_name' => 'Author 11',
            'date_create' => Yii::$app->formatter->asDatetime('now'),
            'date_update' => Yii::$app->formatter->asDatetime('now'),
            'username' => 'admin',
        ]);
    }

    public function testUploadFile()
    {
        $files =  ['Desert.jpg', 'Example.rar', 'Example.xlsx', 'Jellyfish.jpg'];
        $model = $this->generateNewModel();

        // upload for new model
        $model->uploadFiles = [
            $this->getUploadedFiles($files[2]),
            $this->getUploadedFiles($files[3]),
        ];
        expect($model->save())->toBeTrue('Сохранение модели, загрузка файлов');        

        // check uploaded
        $uploadedFiles = $model->lifehackFiles;
        expect($uploadedFiles)->arrayToHaveCount(2);
        foreach($uploadedFiles as $file) {            
            expect(in_array(basename($file->filename), $files))->toBeTrue('Проверка наличия файла в тестовом архиве файлов');            
            expect(Yii::getAlias('@webroot') . $file->filename)->fileToExist('Проверка существования файла');
            expect($file->lifehack->id)->toEqual($model->id, 'Проверка связи модели файла и модели лайфхака');
        }
        $listFiles = $model->getUploadedFiles();
        foreach($listFiles as $file) {
            expect(in_array(basename($file), $files))->toBeTrue();
        }
                
        // delete file        
        $modelFile = $model->lifehackFiles[1];      
        $model->deleteFiles = [$modelFile->id];
        $model->uploadFiles = null;
        expect($model->save())->toBeTrue();
        expect(Yii::getAlias('@webroot') . $modelFile->filename)->fileNotToExist(); 
        $model->refresh();
        expect($model->lifehackFiles)->arrayToHaveCount(1, 'После удаления должен быть 1 файл');

        // upload for update
        $model->uploadFiles = [
            $this->getUploadedFiles($files[0]),
            $this->getUploadedFiles($files[1]),
        ];        
        expect($model->save())->toBeTrue();

        // delete lifehack
        $model->refresh();
        $uploadedFiles2 = $model->lifehackFiles;
        expect($uploadedFiles2)->arrayToHaveCount(3);
        $model->delete();
        foreach($uploadedFiles2 as $file) {
            expect(Yii::getAlias('@webroot') . $file->filename)->fileNotToExist();
        }
    }

    /**
     * @return \yii\web\UploadedFile
     */
    private function getUploadedFiles($image)
    {        
        $img = new \yii\web\UploadedFile();
        $img->tempName = codecept_data_dir('filesForUpload\\' . $image);
        $img->name = basename($img->tempName);
        return $img;
    }

}