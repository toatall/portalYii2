<?php
namespace tests\unit\models\conference;

use app\models\conference\VksKonturTalk;
use app\tests\fixtures\ConferenceFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use app\tests\unit\helpers\SecurityHelper;
use Yii;

class VksKonturTalkTest extends \Codeception\Test\Unit
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

    public function testGeneral()
    {
        $model = new VksKonturTalk();
        expect($model->roleModerator())->toBeString();

        SecurityHelper::login();
        $dropDown = $model->getDropDownOrganizations();
        expect($dropDown)->toBeArray();
        expect($dropDown)->arrayToHaveCount(1);

        SecurityHelper::assignRole(['admin']);
        SecurityHelper::relogin();
        $dropDown2 = $model->getDropDownOrganizations();
        expect($dropDown2)->arrayToHaveCount(3);
    }

    public function testAccess()
    {
        $model = new VksKonturTalk([
            'code_org' => '8600',
        ]);
        
        expect($model->isModerator())->toBeFalse();
        
        SecurityHelper::login();
        expect($model->isModerator())->toBeFalse();

        SecurityHelper::createRole(VksKonturTalk::roleModerator(), true);      
        SecurityHelper::relogin();
        expect($model->isModerator())->toBeTrue();

        SecurityHelper::revokeRoles();
        SecurityHelper::assignRole(['admin']);
        SecurityHelper::relogin();
        expect($model->isModerator())->toBeTrue();

    }

    public function testRuleDateStart()
    {
        $model = new VksKonturTalk([
            'type_conference' => VksKonturTalk::getType(),
            'theme' => 'Some theme',            
            'date_start' => Yii::$app->formatter->asDatetime('2023-05-22 11:00:00'),
            'duration' => '01:30',
        ]);
        expect($model->validate())->toBeTrue();
        expect($model->getErrors('date_start'))->arrayToHaveCount(0);

        $model->date_start = Yii::$app->formatter->asDatetime('2023-05-05 10:00:00');
        expect($model->validate())->toBeFalse();
        expect($model->getErrors('date_start'))->arrayToHaveCount(1);
        expect($model->getErrors('date_start')[0])->stringToContainString('Пересечение с другим ВКС');
    }

    
    
}