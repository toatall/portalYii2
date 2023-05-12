<?php
namespace tests\unit\models\changelegislation;

use app\models\ChangeLegislation;
use app\models\ChangeLegislationSearch;
use app\tests\fixtures\ChangeLegislationFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\RoleFixture;
use app\tests\fixtures\UserFixture;
use app\tests\unit\helpers\SecurityHelper;
use Faker;
use Yii;
use yii\db\Expression;
use yii\db\Query;

class ChangeLegislationSearchTest extends \Codeception\Test\Unit
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
            ChangeLegislationFixture::class,         
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function _before()
    {
        parent::_before();
        $this->faker = Faker\Factory::create();   

        SecurityHelper::login();                
    }

    /**
     * Выборка случайной записи
     * @return array
     */
    private function getRandomRecord($delta = 0)
    {
        $datas = $this->getQueryData();
        $min = min(array_keys($datas));
        $max = max(array_keys($datas));
        if ($delta == 0) {
            $index = rand($min, $max);
        }
        else {
            $index = $max - (int)($max / $delta);
        }
        return $datas[$index];
    }
    
    /**
     * Поиска по id
     */
    public function testSearchById()
    {        
        $randData = $this->getRandomRecord();
        
        $searchModel = new ChangeLegislationSearch();
        $resultSearch = $searchModel->search(['ChangeLegislationSearch' => [
            'id' => $randData['id']
        ]], $randData['is_anti_crisis']);
        $finded = $resultSearch->getModels()[0];                       
        $this->assertNotEmpty($finded);
        $this->equalsArrays($finded->attributes, $randData);
        $this->assertTrue($searchModel->validate());

        $resultSearch2 = $searchModel->search(['ChangeLegislationSearch' => [
            'id' => $randData['id']
        ]], !$randData['is_anti_crisis']);
        $finded2 = $resultSearch2->getModels()[0] ?? null;
        $this->assertEmpty($finded2);
    }

    /**
     * Поиск по тексту
     */
    public function testSearchText()
    {
        $randData = $this->getRandomRecord();
        
        $len = strlen($randData['text']);
        $searchText = substr($randData['text'], ((int) $len / 6), $len - ((int) $len / 4));

        $searchModel = new ChangeLegislationSearch();
        $resultSearch = $searchModel->search(['ChangeLegislationSearch' => [
            'searchText' => $searchText,
        ]], $randData['is_anti_crisis'], false);
        $finded = $resultSearch->getModels()[0] ?? null;
        $this->equalsArrays($randData, $finded->attributes);

        $resultSearch2 = $searchModel->search(['ChangeLegislationSearch' => [
            'searchText' => $searchText . 'some text',
        ]], $randData['is_anti_crisis'], false);
        $finded2 = $resultSearch2->getModels()[0] ?? null;
        $this->assertEmpty($finded2);
    }

    /**
     * Поиск по дате
     */
    public function testSearchDate()
    {        
        $randData = $this->getRandomRecord(5);
        
        $this->findByDateField('searchDate1', '>=', $randData['date_doc'], $randData['is_anti_crisis']);
        $this->findByDateField('searchDate1', '>=', $randData['date_doc_1'], $randData['is_anti_crisis']);
        $this->findByDateField('searchDate1', '>=', $randData['date_doc_2'], $randData['is_anti_crisis']);
        $this->findByDateField('searchDate1', '>=', $randData['date_doc_3'], $randData['is_anti_crisis']);

        $this->findByDateField('searchDate2', '<=', $randData['date_doc'], $randData['is_anti_crisis']);
        $this->findByDateField('searchDate2', '<=', $randData['date_doc_1'], $randData['is_anti_crisis']);
        $this->findByDateField('searchDate2', '<=', $randData['date_doc_2'], $randData['is_anti_crisis']);
        $this->findByDateField('searchDate2', '<=', $randData['date_doc_3'], $randData['is_anti_crisis']);

    }

    protected function findByDateField($textFieldName, $operator, $date, $isAntiCrisis)
    {
        $records = (new Query())
            ->from(ChangeLegislation::tableName())
            ->where("(date_doc {$operator} cast(:d as date) or date_doc_1 {$operator} cast(:d2 as date)"
                ." or date_doc_2 {$operator} cast(:d3 as date) or date_doc_3 {$operator} cast(:d4 as date))", [
                    ':d'  => $date,
                    ':d2' => $date,
                    ':d3' => $date,
                    ':d4' => $date,
                ])
            ->andWhere(['is_anti_crisis' => $isAntiCrisis])
            ->all();
        
        $searchModel = new ChangeLegislationSearch();
        $resultSearch = $searchModel->search(['ChangeLegislationSearch' => [
             $textFieldName => $date,
        ]], $isAntiCrisis);

        $this->assertEquals(count($records), count($resultSearch->getModels()));
    }


    /**
     * Сравнение 2х массивов с данными из ChangeLegislation.
     * Дата приводится к одному формату
     * @param array $array1 
     * @param array $array2
     */
    private function equalsArrays($array1, $array2)
    {
        $this->assertEquals($array1['id'], $array2['id']);
        $this->assertEquals($array1['type_doc'], $array2['type_doc']);
        $this->assertEquals($this->asDate($array1['date_doc']), $this->asDate($array2['date_doc']));
        $this->assertEquals($array1['number_doc'], $array2['number_doc']);
        $this->assertEquals($array1['name'], $array2['name']);
        $this->assertEquals($this->asDate($array1['date_doc_1']), $this->asDate($array2['date_doc_1']));
        $this->assertEquals($this->asDate($array1['date_doc_2']), $this->asDate($array2['date_doc_2']));
        $this->assertEquals($this->asDate($array1['date_doc_3']), $this->asDate($array2['date_doc_3']));
        $this->assertEquals($array1['status_doc'], $array2['status_doc']);
        $this->assertEquals($array1['text'], $array2['text']);
    }

    /**
     * Форматирование даты
     * @param string $date
     * @return string
     */
    private function asDate($date)
    {
        return Yii::$app->formatter->asDate($date);
    }    

    /**
     * Получение всех данных проиндексированных по id
     * @return array
     */
    protected function getQueryData()
    {
        return (new Query())
            ->from(ChangeLegislation::tableName())
            ->indexBy('id')
            ->all();
    }

}