<?php
namespace tests\unit\models\conference;

use app\models\conference\AbstractConference;
use app\models\conference\VksFnsSearch;
use app\models\conference\VksKonturTalkSearch;
use app\tests\fixtures\ConferenceFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use PHPUnit\Framework\MockObject\MockObject;
use Yii;

class VksKonturTalkSearchTest extends \Codeception\Test\Unit
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
        $record = $this->tester->grabFixture('conferences', 51);
        
        // by id
        $resultId = $this->search((new VksKonturTalkSearch()), [
            'id' => $record->id,
        ]);
        expect($resultId)->notToBeEmpty();
        expect($resultId[0]['id'])->toEqual($record['id']);       
        
        // by code_org
        $resultCodeOrg = $this->search((new VksKonturTalkSearch()), [
            'code_org' => '8600',
        ]);
        expect($resultCodeOrg)->arrayToHaveCount(1);

        // by duration
        $resultDuration = $this->search((new VksKonturTalkSearch()), [
            'duration' => '02:20',        
        ]);
        expect($resultDuration)->arrayToHaveCount(1);
        expect($resultDuration[0]['duration'])->toEqual('02:20');

        // by date_start
        $resultDateStart = $this->search((new VksKonturTalkSearch()), [
            'date_start' => Yii::$app->formatter->asDatetime('2023-05-11 14:40:00'),        
        ]);
        expect($resultDateStart)->arrayToHaveCount(1);

        // by theme
        $resultTheme = $this->search((new VksKonturTalkSearch()), [
            'theme' => '2',        
        ]);
        expect($resultTheme)->arrayToHaveCount(1);
        expect($resultTheme[0]['theme'])->toEqual('Vks Kontur Talk 2');
        
        // by place
        $resultPlace = $this->search((new VksKonturTalkSearch()), [
            'place' => '601',        
        ]);       
        expect($resultPlace)->arrayToHaveCount(1);
        expect($resultPlace[0]['place'])->toEqual('601 каб.');

        /** @var VksKonturTalkSearch|MockObject $model */
        $model = $this->getMockBuilder(VksKonturTalkSearch::class)
            ->onlyMethods(['validate', 'formName'])
            ->getMock();
        $model->method('validate')->willReturn(false);
        $model->method('formName')->willReturn('VksKonturTalkSearch');
        $resultFalseValidate = $this->search($model, [
            'id' => $record['id'],
        ]);
        expect($resultFalseValidate)->arrayToHaveCount(10);
        
     }

    /**
     * 
     */
    private function search(VksKonturTalkSearch $model, array $searchAttributes) 
    {
        $search = $model->search(['VksKonturTalkSearch' => $searchAttributes]);
        return $search->getModels();
    }
    
}