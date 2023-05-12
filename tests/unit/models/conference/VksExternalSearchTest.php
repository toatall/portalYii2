<?php
namespace tests\unit\models\conference;

use app\models\conference\AbstractConference;
use app\models\conference\EventsAll;
use app\models\conference\VksExternalSearch;
use app\tests\fixtures\ConferenceFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use PHPUnit\Framework\MockObject\MockObject;
use Yii;

class VksExternalSearchTest extends \Codeception\Test\Unit
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
        $record = $this->tester->grabFixture('conferences', 41);
        
        // by id
        $resultId = $this->search((new VksExternalSearch()), [
            'id' => $record->id,
        ]);
        expect($resultId)->notToBeEmpty();
        expect($resultId[0]['id'])->toEqual($record['id']);

        // by type_conference
        $resultTypeConf = $this->search((new VksExternalSearch()), [
            'type_conference' => AbstractConference::TYPE_VKS_EXTERNAL,
        ]);
        expect($resultTypeConf)->arrayToHaveCount(2);
        
        // by duration
        $resultDuration = $this->search((new VksExternalSearch()), [
            'duration' => '03:00',        
        ]);
        expect($resultDuration)->arrayToHaveCount(1);
        expect($resultDuration[0]['duration'])->toEqual('03:00');

        // by date_start
        $resultDateStart = $this->search((new VksExternalSearch()), [
            'date_start' => Yii::$app->formatter->asDatetime('2023-05-02 16:10:00'),        
        ]);
        expect($resultDateStart)->arrayToHaveCount(1);

        // by theme
        $resultTheme = $this->search((new VksExternalSearch()), [
            'theme' => '1',        
        ]);
        expect($resultTheme)->arrayToHaveCount(1);
        expect($resultTheme[0]['theme'])->toEqual('Vks External 1');
        
        // by place
        $resultPlace = $this->search((new VksExternalSearch()), [
            'place' => '202',        
        ]);
        expect($resultPlace)->arrayToHaveCount(1);
        expect($resultPlace[0]['place'])->toEqual('202 каб.');

        /** @var VksExternalSearch|MockObject $model */
        $model = $this->getMockBuilder(VksExternalSearch::class)
            ->onlyMethods(['validate', 'formName'])
            ->getMock();
        $model->method('validate')->willReturn(false);
        $model->method('formName')->willReturn('VksExternalSearch');
        $resultFalseValidate = $this->search($model, [
            'id' => $record['id'],
        ]);
        expect($resultFalseValidate)->arrayToHaveCount(10);
        
     }

    /**
     * 
     */
    private function search(VksExternalSearch $model, array $searchAttributes) 
    {
        $search = $model->search(['VksExternalSearch' => $searchAttributes]);
        return $search->getModels();
    }
    
}