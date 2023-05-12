<?php
namespace tests\unit\models\lifehack;

use app\models\lifehack\Lifehack;
use app\models\lifehack\LifehackFile;
use app\models\lifehack\LifehackLike;
use app\models\lifehack\LifehackSearch;
use app\models\lifehack\LifehackTags;
use app\tests\fixtures\LifeHackFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use app\tests\unit\helpers\SecurityHelper;
use PHPUnit\Framework\MockObject\MockObject;

class LifeHackTest extends \Codeception\Test\Unit
{       

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

    public function testBasic()
    {
        $model = new Lifehack();
        expect($model->rules())->toBeArray();

        // tags
        $tags = ['#tag1', '#tag2', '#newTag3'];
        $tagsStr = implode('/', $tags);
        $model->tags = $tagsStr;
        expect($model->getTagsArray())
            ->toBeArray()
            ->toEqual($tags);
        
        $model->setTagsArray($tags);
        expect($model->tags)
            ->toBeString()
            ->toEqual($tagsStr);
        
        // empty tags
        $model->setTagsArray(null);
        expect($model->tags)->toBeNull();
        expect($model->getTagsArray())->toBeEmpty();
    }

    public function testRole()
    {
        SecurityHelper::login();
        expect(Lifehack::isEditor())->toBeFalse('У пользователя без ролей admin и lifehack-editor прав на редактирование нет');

        SecurityHelper::createRole('lifehack-editor', true);
        SecurityHelper::relogin();
        expect(Lifehack::isEditor())->toBeTrue('Должны быть права редактора у роли lifehack-editor');

        SecurityHelper::revokeRoles();
        SecurityHelper::assignRole(['admin']);
        SecurityHelper::relogin();
        expect(Lifehack::isEditor())->toBeTrue('Должны быть права редактора у роли admin');

    }

    public function testRelations()
    {
        /** @var Lifehack $model */
        $model = $this->tester->grabFixture('lifehacks', 3);
        $orgModel = $model->organizationModel;
        expect($orgModel->code)->toEqual('8600');
    }


    public function testSearch()
    {      
        $resultOrgCode = (new LifehackSearch())->search(['LifehackSearch' => [
            'org_code' => '8600',
        ]]);
        foreach($resultOrgCode->getModels() as $item) {
            /** @var Lifehack $item */
            expect($item->org_code)->toEqual('8600');
        }

        $searchOrgName1 = (new LifehackSearch())->search(['LifehackSearch' => [
            'searchOrgName' => '8602',
        ]]);
        $result1 = $searchOrgName1->getModels();
        expect($result1)->arrayToHaveCount(1);
        
        $searchOrgName2 = (new LifehackSearch())->search(['LifehackSearch' => [
            'searchOrgName' => 'Author 1',
        ]]);
        $result2 = $searchOrgName2->getModels();
        expect($result2)->arrayToHaveCount(2);
    }

    public function testSearchNotValid()
    {
        $grabRecord = $this->tester->grabFixture('lifehacks', 4);

        /** @var LifehackSearch|MockObject $model */
        $model = $this->getMockBuilder(LifehackSearch::class)
            ->onlyMethods(['validate', 'formName'])
            ->getMock();
        $model->method('validate')->willReturn(false);
        $model->method('formName')->willReturn('LifehackSearch');
       
        $search1 = $model->search(['LifehackSearch' => [
            'id' => $grabRecord->id,
        ]]);
        $result1 = $search1->getModels();

        expect($result1)->arrayToHaveCount(5);        
    }    

}