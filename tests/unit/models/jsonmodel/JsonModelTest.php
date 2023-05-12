<?php
namespace tests\unit\models\jsonmodel;

use app\models\json\JsonQuery;
use app\tests\unit\helpers\ReflectionHelper;
use Faker;
use app\tests\unit\models\jsonmodel\SomeModel;

class JsonModelTest extends \Codeception\Test\Unit
{          

    /**
     * @var Faker\Generator
     */
    private $faker; 


    /**
     * {@inheritdoc}
     */
    protected function _before()
    {
        parent::_before();
        $this->faker = Faker\Factory::create();                
    }
    
    
    public function testWhere()
    {        
        /** @var SomeModel $find */
        $find = SomeModel::find()->where(fn($item) => $item['id'] == 2)->one();
        $this->assertNotEmpty($find);
        $this->assertEquals('2', $find->id);

        $findNull = SomeModel::find()->where(fn($item) => $item['id'] == 100);
        expect($findNull->one())->toBeNull();
        expect($findNull->all())->toBeNull();
    }

    
    public function testOrder()
    {
        $find = SomeModel::find();

        // ASC
        /** @var SomeModel[] $resAsc */
        $resAsc = $find->order('code')->all();
        $arrAsc = [
            $resAsc[0]->code,
            $resAsc[1]->code, 
            $resAsc[2]->code, 
            $resAsc[3]->code,
        ];
        expect(['8601', '8601', '8602', '8603'])->toEqual($arrAsc);
        
        // DESC
        /** @var SomeModel[] $resDesc */
        $resDesc = $find->order('code', SORT_DESC)->all();
        $arrDesc = [
            $resDesc[3]->code,
            $resDesc[2]->code, 
            $resDesc[1]->code, 
            $resDesc[0]->code,
        ];
        expect(['8601', '8601', '8602', '8603'])->toEqual($arrDesc);
    }

    public function testGroup()
    {
        /** @var SomeModel[] $res */
        $res = array_keys(SomeModel::find()->group('code')->all());        
        sort($res);
        expect(['8601', '8602', '8603'])->toEqual($res);

        $jsonQuery = new JsonQuery([], null);
        ReflectionHelper::setPropertyValue($jsonQuery, 'group', 'code');
        $resArr = ReflectionHelper::invokeMethod($jsonQuery, 'groupingData', ['data' => [
            ['id' => 1, 'code' => '8600', 'name' => 'Name 1'],
            ['id' => 2, 'code' => '8601', 'name' => 'Name 2'],
            ['id' => 3, 'code' => '8602', 'name' => 'Name 3'],
            ['id' => 4, 'code' => '8600', 'name' => 'Name 4'],
            ['id' => 5, 'code' => '8601', 'name' => 'Name 5'],
            ['id' => 6, 'code' => '8602', 'name' => 'Name 6'],
        ]]);        
        expect($resArr)->arrayToHaveKey('8600');
        expect($resArr)->arrayToHaveKey('8601');
        expect($resArr)->arrayToHaveKey('8602');
        expect($resArr)->arrayNotToHaveKey('8603');        
    }

    public function testIndex()
    {
        $items = SomeModel::find()->index()->all();
        foreach($items as $item) {
            expect($item->index)->toBeString()->notToBeEmpty();
        }
    }


    public function testAttributes()
    {
        $model = new SomeModel();
        expect($model->attributeLabels())->toBeArray();
        expect($model->attributeLabelByName('id'))->toEqual('Id');
        expect($model->attributeLabelByName('notExistsAttr'))->toBeNull();
    }


    public function testFileWithKey()
    {       
        /** @var SomeModel $find */        
        SomeModel::$fileName = '/tests/_data/dataKey.json';
        $all = SomeModel::find()->all(); 
        expect($all)->arrayToHaveCount(3);
    }


    public function testFileIsNull()
    {
        SomeModel::$fileName = '/tests/_data/dataNull.json';
        $res = SomeModel::find()->all(); 
        expect($res)->toBeEmpty();
    }


    public function testFileNotExists()
    {       
        /** @var SomeModel $find */
        $this->expectException(\yii\base\Exception::class);
        SomeModel::$fileName = '/path/no-file-exists.json';        
        SomeModel::find()->one(); 
    }
    
}