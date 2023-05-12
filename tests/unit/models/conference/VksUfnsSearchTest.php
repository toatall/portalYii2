<?php
namespace tests\unit\models\conference;

use app\models\conference\VksUfnsSearch;
use app\tests\fixtures\ConferenceFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use PHPUnit\Framework\MockObject\MockObject;
use Yii;

class VksUfnsSearchTest extends \Codeception\Test\Unit
{       

    /**
     * @var \UnitTester
     */
    public $tester;

    /**
     * {@inheritdoc}
     */
    public function _fixtures()
    {        
        return [         
            UserFixture::class,               
            OrganizationFixture::class,
            'conferences' => ConferenceFixture::class,         
        ];
    }    

    public function testSearch()
    {
        $record = $this->tester->grabFixture('conferences', 11);
        
        // by id
        $resultId = $this->search((new VksUfnsSearch()), [
            'id' => $record->id,
        ]);
        expect($resultId)->notToBeEmpty();
        expect($resultId[0]['id'])->toEqual($record['id']);

        // by duration
        $resultDuration = $this->search((new VksUfnsSearch()), [
            'duration' => '00:30',        
        ]);
        expect($resultDuration)->arrayToHaveCount(1);
        expect($resultDuration[0]['duration'])->toEqual('00:30');

        // by date_start
        $resultDateStart = $this->search((new VksUfnsSearch()), [
            'date_start' => Yii::$app->formatter->asDatetime('2023-05-02 10:30:00'),        
        ]);
        expect($resultDateStart)->arrayToHaveCount(1);

        // by theme
        $resultTheme = $this->search((new VksUfnsSearch()), [
            'theme' => '1',        
        ]);
        expect($resultTheme)->arrayToHaveCount(1);
        expect($resultTheme[0]['theme'])->toEqual('Vks Ufns 1');
        
        // by place
        $resultPlace = $this->search((new VksUfnsSearch()), [
            'place' => '202',        
        ]);       
        expect($resultPlace)->arrayToHaveCount(1);
        expect($resultPlace[0]['place'])->toEqual('202 каб.');

        /** @var VksUfnsSearch|MockObject $model */
        $model = $this->getMockBuilder(VksUfnsSearch::class)
            ->onlyMethods(['validate', 'formName'])
            ->getMock();
        $model->method('validate')->willReturn(false);
        $model->method('formName')->willReturn('VksUfnsSearch');
        $resultFalseValidate = $this->search($model, [
            'id' => $record['id'],
        ]);
        expect($resultFalseValidate)->arrayToHaveCount(10);
        
     }

    /**
     * 
     */
    private function search(VksUfnsSearch $model, array $searchAttributes) 
    {
        $search = $model->search(['VksUfnsSearch' => $searchAttributes]);
        return $search->getModels();
    }
    
}