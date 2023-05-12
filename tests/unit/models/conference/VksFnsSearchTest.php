<?php
namespace tests\unit\models\conference;

use app\models\conference\AbstractConference;
use app\models\conference\VksExternalSearch;
use app\models\conference\VksFnsSearch;
use app\tests\fixtures\ConferenceFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use PHPUnit\Framework\MockObject\MockObject;
use Yii;

class VksFnsSearchTest extends \Codeception\Test\Unit
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
        $record = $this->tester->grabFixture('conferences', 22);
        
        // by id
        $resultId = $this->search((new VksFnsSearch()), [
            'id' => $record->id,
        ]);
        expect($resultId)->notToBeEmpty();
        expect($resultId[0]['id'])->toEqual($record['id']);       
        
        // by duration
        $resultDuration = $this->search((new VksFnsSearch()), [
            'duration' => '02:00',        
        ]);
        expect($resultDuration)->arrayToHaveCount(1);
        expect($resultDuration[0]['duration'])->toEqual('02:00');

        // by date_start
        $resultDateStart = $this->search((new VksFnsSearch()), [
            'date_start' => Yii::$app->formatter->asDatetime('2023-05-01 12:30:00'),        
        ]);
        expect($resultDateStart)->arrayToHaveCount(1);

        // by theme
        $resultTheme = $this->search((new VksFnsSearch()), [
            'theme' => '2',        
        ]);
        expect($resultTheme)->arrayToHaveCount(1);
        expect($resultTheme[0]['theme'])->toEqual('Vks Fns 2');
        
        // by place
        $resultPlace = $this->search((new VksFnsSearch()), [
            'place' => '303',        
        ]);
        expect($resultPlace)->arrayToHaveCount(1);
        expect($resultPlace[0]['place'])->toEqual('303 каб.');

        /** @var VksFnsSearch|MockObject $model */
        $model = $this->getMockBuilder(VksFnsSearch::class)
            ->onlyMethods(['validate', 'formName'])
            ->getMock();
        $model->method('validate')->willReturn(false);
        $model->method('formName')->willReturn('VksFnsSearch');
        $resultFalseValidate = $this->search($model, [
            'id' => $record['id'],
        ]);
        expect($resultFalseValidate)->arrayToHaveCount(10);
        
     }

    /**
     * 
     */
    private function search(VksFnsSearch $model, array $searchAttributes) 
    {
        $search = $model->search(['VksFnsSearch' => $searchAttributes]);
        return $search->getModels();
    }
    
}