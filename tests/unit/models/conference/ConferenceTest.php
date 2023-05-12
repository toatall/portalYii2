<?php
namespace tests\unit\models\conference;

use app\models\conference\AbstractConference;
use app\models\conference\Conference;
use app\models\conference\ConferenceSearch;
use app\models\conference\EventsAll;
use app\models\conference\VksExternal;
use app\models\conference\VksExternalSearch;
use app\models\conference\VksFns;
use app\models\conference\VksKonturTalk;
use app\models\conference\VksKonturTalkSearch;
use app\models\conference\VksUfns;
use app\models\conference\VksUfnsSearch;
use app\tests\fixtures\ConferenceFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use app\tests\unit\helpers\ReflectionHelper;
use app\tests\unit\helpers\SecurityHelper;
use PHPUnit\Framework\MockObject\MockObject;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class ConferneceTest extends \Codeception\Test\Unit
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

    /**
     * Базовые тесты
     */
    public function testBasicConference()
    {                 
        /**
         * @var Conference $conference
         */
        $conference = $this->tester->grabFixture('conferences', 32);

        expect($conference->getParamNotifyEmail())->toBeString();
        expect($conference->getTime_start())->toEqual(Yii::$app->formatter->asTime($conference->date_start, 'short'));
        expect(ReflectionHelper::invokeMethod($conference, 'getParamNotifyEmail'))->toBeString();
        expect(ReflectionHelper::invokeMethod($conference, 'getParamEmailAddressAppeal'))->toBeString();
        expect($conference->dropDownListFormat())->toBeArray();
        expect($conference->dropDownListMaterials())->toBeArray();

        expect(Conference::getTypes())->toBeArray();

        expect(Conference::getModule())->toBeString();
        expect(Conference::getTypeLabel())->toBeString();
        expect(Conference::getType())->toBeNumeric();

        expect(Conference::getLabelType('conference'))->notToBeEmpty();
                            
    }

    public function testBasicVksExternal()
    {
        expect(VksExternal::getModule())->toBeString();
        expect(VksExternal::getType())->toBeNumeric();     
        expect(VksExternal::getTypeLabel())->toBeString();
        expect((new VksExternal())->rules())->toBeArray();
        expect((new VksExternal())->attributeLabels())->toBeArray();        
    }
    public function testBasicVksFns()
    {
        expect(VksFns::getModule())->toBeString();
        expect(VksFns::getType())->toBeNumeric();     
        expect(VksFns::getTypeLabel())->toBeString();        
    }

    public function testBasicVksUfns()
    {
        expect(VksUfns::getModule())->toBeString();
        expect(VksUfns::getType())->toBeNumeric();     
        expect(VksUfns::getTypeLabel())->toBeString();   
        expect((new VksUfns())->isView())->toBeTrue();     
    }

    public function testBasicVksKonturTalk()
    {
        expect(VksKonturTalk::getModule())->toBeString();
        expect(VksKonturTalk::getType())->toBeNumeric();     
        expect(VksKonturTalk::getTypeLabel())->toBeString();        
    }


    public function testAbstractConference()
    {
        // findToday
        $resFind1 = Conference::findToday()->all();
        expect($resFind1)->toBeEmpty();
        $newConference = new Conference([            
            'type_conference' => Conference::getType(),
            'theme' => 'Conference 3',
            'responsible' => 'Labore sunt do irure aliqua commodo cupidatat excepteur cillum pariatur eu ex.',
            'members_people' => 'Est dolor velit cillum ex laborum occaecat in occaecat commodo culpa ea. Minim excepteur sunt duis exercitation et adipisicing commodo fugiat aute proident exercitation reprehenderit. Exercitation eu quis sint eu laborum laboris Lorem. Dolor incididunt do laboris ex sunt sint aliquip excepteur laborum officia eu ipsum amet exercitation. Id veniam ad officia eiusmod cillum est tempor id. Adipisicing aute excepteur cillum consequat officia occaecat ea adipisicing ipsum. Aute id amet irure dolore ut sunt labore enim duis.',
            'members_organization' => 'Duis adipisicing elit ut proident irure veniam esse veniam.',
            'date_start' => Yii::$app->formatter->asDateTime('now'),
            'time_start_msk' => 0,
            'duration' => '00:30',
            'is_confidential' => 0,
            'arrPlace' => ['444 каб.'],
            'note' => 'some note',
            'status' => 'complete',
            'editor' => 'system',
        ]);
        SecurityHelper::login('admin');
        $newConference->validate();        
        expect($newConference->save())->toBeTrue();
        $resFind2 = Conference::findToday()->all();            
        expect($resFind2)->arrayToHaveCount(1);

        // findActual
        $resActual = Conference::findActual()->all();        
        expect($resActual)->arrayToHaveCount(3);

        // events today
        expect(AbstractConference::eventsToday())->toBeString();

        // events color
        $testModel = new Conference([
            'type_conference' => null,
        ]);
        expect($testModel->getEventColor())->toBeEmpty();
        $testModel->type_conference = AbstractConference::TYPE_CONFERENCE;
        expect($testModel->getEventColor())->notToBeEmpty();
        $testModel->type_conference = AbstractConference::TYPE_VKS_EXTERNAL;
        expect($testModel->getEventColor())->notToBeEmpty();
        $testModel->type_conference = AbstractConference::TYPE_VKS_FNS;
        expect($testModel->getEventColor())->notToBeEmpty();
        $testModel->type_conference = AbstractConference::TYPE_VKS_UFNS;
        expect($testModel->getEventColor())->notToBeEmpty();

        // typelabel
        $testModel2 = new Conference([
            'type_conference' => 'mock name',
        ]);
        expect($testModel2->typeLabel())->toEqual('mock name');
        $testModel2->type_conference = AbstractConference::TYPE_CONFERENCE;
        expect($testModel2->typeLabel())->notToBeEmpty();
        $testModel2->type_conference = AbstractConference::TYPE_VKS_EXTERNAL;
        expect($testModel2->typeLabel())->notToBeEmpty();
        $testModel2->type_conference = AbstractConference::TYPE_VKS_FNS;
        expect($testModel2->typeLabel())->notToBeEmpty();
        $testModel2->type_conference = AbstractConference::TYPE_VKS_UFNS;
        expect($testModel2->typeLabel())->notToBeEmpty();
    }

    public function testIsFinished()
    {
        /** @var AbstractConference $model */
        $model = $this->tester->grabFixture('conferences', 41);
        expect($model->isFinished())->toBeTrue();
        $model->date_start = Yii::$app->formatter->asDatetime((time() + (60 * 60 * 24 * 1)));
        expect($model->isFinished())->toBeFalse();
    }

    public function testCross()
    {
        /** @var AbstractConference $testModel */
        $testModel = $this->tester->grabFixture('conferences', 31); // 2023-05-05 11:00:00 | 202        
        $testModel->date_end = $this->getDateEnd($testModel->date_start, $testModel->duration);
        
        expect($testModel->isCrossedI())->toBeNull();
        expect($testModel->isCrossedMe())->toBeNull();

        SecurityHelper::login();

        // create a new conference
        $newModel = new Conference([
            'type_conference' => AbstractConference::TYPE_CONFERENCE,
            'date_start' => Yii::$app->formatter->asDateTime('2023-05-05 11:20:00'),
            'theme' => 'Theme conference',
            'duration' => '01:00',
            'arrPlace' => ['202 каб.'],
            'status' => Conference::STATUS_COMPLETE,
        ]);
        expect($newModel->save())->toBeTrue();

        // crossed testModel
        expect($testModel->isCrossedMe())->notToBeNull();
        expect($testModel->isCrossedI())->toBeNull();

        // testModel crossed
        $newModel->date_start = Yii::$app->formatter->asDateTime('2023-05-05 10:30:00');
        expect($newModel->save())->toBeTrue();
        expect($testModel->isCrossedI())->notToBeNull();
        expect($testModel->isCrossedMe())->toBeNull();

    }

    private function getDateEnd($dateStart, $timeDuration)
    {
        $duration = explode(':', $timeDuration);
        return Yii::$app->formatter->asDatetime(strtotime($dateStart) 
            + (intval($duration[0]) * 60 * 60) + (intval($duration[1]) * 60));
    }

    public function testConferenceSearch()
    {
        // ConferenceSearch
        $modelConferenceSearch = new ConferenceSearch();
        $this->assertIsArray($modelConferenceSearch->rules());
        
        $this->searchByAttributes([
            'place' => '101 каб.',
        ]);
        $this->searchByAttributes([
            'duration' => '01:00',
        ]);

        $record = $this->tester->grabFixture('conferences', '31');
        /** @var ConferenceSearch|MockObject $model */
        $model = $this->getMockBuilder(ConferenceSearch::class)
            ->onlyMethods(['validate', 'formName'])
            ->getMock();
        $model->method('validate')->willReturn(false);
        $model->method('formName')->willReturn('ConferenceSearch');
        $resultFalseValidate = $this->search($model, [
            'id' => $record['id'],
        ]);
        expect($resultFalseValidate)->arrayToHaveCount(10);
    }

    private function search(ConferenceSearch $model, array $searchAttributes) 
    {
        $search = $model->search(['ConferenceSearch' => $searchAttributes]);
        return $search->getModels();
    }

    private function searchByAttributes($attributes)
    {
        $modelConferenceSearch = new ConferenceSearch();

        $search = $modelConferenceSearch->search(['ConferenceSearch' => $attributes]);
        $models = $search->getModels(); 

        $query = $this->queryConference(array_merge($attributes, [            
            'type_conference' => Conference::TYPE_CONFERENCE,
        ]));

        $idModels = ArrayHelper::map($models, 'id', 'id');
        $idQuery = ArrayHelper::map($query, 'id', 'id');

        expect($idModels)->toEqual($idQuery);
    }


    private function queryConference($condition)
    {
        return (new Query())
            ->from('{{%conference}}')
            ->where($condition)
            ->all();
    }
   

    public function testGetUrlAdmin()
    {
        $model = new Conference([
            'type_conference' => AbstractConference::TYPE_VKS_FNS,
        ]);
        expect($model->getUrlAdmin())->notToBeEmpty();
        $model->type_conference = AbstractConference::TYPE_VKS_UFNS;
        expect($model->getUrlAdmin())->notToBeEmpty();
        $model->type_conference = AbstractConference::TYPE_CONFERENCE;
        expect($model->getUrlAdmin())->notToBeEmpty();
        $model->type_conference = AbstractConference::TYPE_VKS_EXTERNAL;
        expect($model->getUrlAdmin())->notToBeEmpty();
        $model->type_conference = 39483;
        expect($model->getUrlAdmin())->toBeNull();
    }

    public function testStrType()
    {
        $model = new Conference([
            'type_conference' => AbstractConference::TYPE_CONFERENCE,
        ]);
        expect($model->strType())->notToBeNull();
        $model->type_conference = AbstractConference::TYPE_VKS_FNS;
        expect($model->strType())->notToBeNull();
        $model->type_conference = AbstractConference::TYPE_VKS_UFNS;
        expect($model->strType())->notToBeNull();
        $model->type_conference = AbstractConference::TYPE_VKS_EXTERNAL;
        expect($model->strType())->notToBeNull();
        $model->type_conference = 343;
        expect($model->strType())->toBeNull();
    }

}