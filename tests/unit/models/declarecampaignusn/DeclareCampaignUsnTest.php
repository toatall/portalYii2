<?php
namespace tests\unit\models\declarecampaignusn;

use app\models\DeclareCampaignUsn;
use app\tests\fixtures\DeclareCampaignUsnFixture;
use app\tests\unit\helpers\ReflectionHelper;
use app\tests\unit\helpers\SecurityHelper;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;

class DeclareCampaignUsnTest extends \Codeception\Test\Unit
{       

    /**
     * {@inheritdoc}
     */
    public function _fixtures()
    {        
        return [            
            DeclareCampaignUsnFixture::class,
        ];
    }

    public function _before()
    {
        SecurityHelper::login();
    }

    /**
     * Тестирование простых функций
     * 
     * @see DeclareCampaignUsn::tableName()
     * @see DeclareCampaignUsn::behaviors()
     * @see DeclareCampaignUsn::rules()
     * @see DeclareCampaignUsn::getRoleModerator()
     * @see DeclareCampaignUsn::getReportsYears()
     * @see DeclareCampaignUsn::attributeLabels()
     */
    public function testSimple()
    {
        $model = new DeclareCampaignUsn();
        $this->assertIsString($model->tableName());
        $this->assertIsArray($model->behaviors());
        $this->assertIsArray($model->rules());
        $this->assertIsString($model->getRoleModerator());  
        $this->assertIsArray($model->getReportsYears());      
        $this->assertIsArray($model->attributeLabels());
    }

    /**
     * Тестирование функции получения данных за последнюю дату
     * 
     * @see DeclareCampaignUsn::findWithLastDate()
     */
    public function testFindWithLastDate()
    {
        $this->clearCache();

        $modelData = DeclareCampaignUsn::findWithLastDate();
        $maxDate = Yii::$app->formatter->asDate('2023-05-04');
        foreach($modelData as $item) {
            /** @var DeclareCampaignUsn $item */
            $this->assertEquals($maxDate, Yii::$app->formatter->asDate($item->date));
            $this->assertIsInt((int)$item->count_np);
            $this->assertIsInt((int)$item->count_np_ul);
            $this->assertIsInt((int)$item->count_np_ip);
            $this->assertIsInt((int)$item->count_np_provides_reliabe_declare);
            $this->assertIsInt((int)$item->count_np_provides_not_required);
        }

        DeclareCampaignUsn::deleteAll();
        $this->clearCache();        
        $deletedData = DeclareCampaignUsn::findWithLastDate();
        $this->assertEmpty($deletedData);
    }

    /**
     * Тестирование функций получения даннных за предыдущую дату
     * 
     * @see DeclareCampaignUsn::getPreviousData()
     * @see DeclareCampaignUsn::getPrevious_count_np_provides_reliabe_declare()
     * @see DeclareCampaignUsn::getPrevious_count_np_provides_not_required()
     * @see DeclareCampaignUsn::getPrevious_date() 
     */
    public function testGetPreviousDate()
    {
        $this->clearCache();
        /** @var DeclareCampaignUsn $model */
        $model = DeclareCampaignUsn::find()
            ->where('date = cast(:d as date)', [':d' => $this->asDate('2023-05-03')])
            ->one();
        $model->afterFind();
        $previousData = ReflectionHelper::getProperty($model, '_previousData');
        $this->assertEquals($this->asDate($previousData->date), $this->asDate('2023-05-02'));
        $this->assertEquals($previousData->count_np_provides_reliabe_declare, $model->getPrevious_count_np_provides_reliabe_declare());
        $this->assertEquals($previousData->count_np_provides_not_required, $model->getPrevious_count_np_provides_not_required());
        $this->assertEquals($this->asDate($previousData->date), $this->asDate($model->getPrevious_date()));
    }

    /**
     * Тестирование функции получения моделей для массового сохранения
     * 
     * @see DeclareCampaignUsn::getModels()
     */
    public function testGetModels()
    {
        $this->clearCache();
        $orgs = $this->getOrgs();

        // DeclareCampaignUsn models must be is new
        $dataNew = DeclareCampaignUsn::getModels(date('Y'), $this->asDate('2023-05-05'));      
        foreach($orgs as $org) {
            $this->assertTrue($dataNew[$org]->isNewRecord);            
        }   

        // DeclareCampaignUsn models must not be is new
        $dataExist = DeclareCampaignUsn::getModels(date('Y'), $this->asDate('2023-05-04'));      
        foreach($orgs as $org) {
            $this->assertFalse($dataExist[$org]->isNewRecord);
        }
    }   

    /**
     * Тестирование функции массового сохранения
     * 
     * @see DeclareCampaignUsn::saveModels()
     */
    public function testSaveModels()
    {
        $this->clearCache();
        $orgs = $this->getOrgs();

        $dataNew = DeclareCampaignUsn::getModels(date('Y'), $this->asDate('2023-05-05'));
        foreach($orgs as $org) {
            $this->assertTrue($dataNew[$org]->isNewRecord);
            /** @var DeclareCampaignUsn $item */
            $dataNew[$org]->count_np = 7;            
            $dataNew[$org]->count_np_ul = 3;
            $dataNew[$org]->count_np_provides_reliabe_declare = 2;
            $dataNew[$org]->count_np_provides_not_required = 1;     
        }        
        $this->assertFalse(DeclareCampaignUsn::saveModels($dataNew));
        
        foreach($orgs as $org) {
            $this->assertTrue($dataNew[$org]->isNewRecord);
            /** @var DeclareCampaignUsn $item */            
            $dataNew[$org]->count_np_ip = 4;            
        }    
        $this->assertTrue(DeclareCampaignUsn::saveModels($dataNew));
        
        $dataSaved = DeclareCampaignUsn::getModels(date('Y'), $this->asDate('2023-05-05'));      
        foreach($orgs as $org) {
            /** @var DeclareCampaignUsn $item */
            $this->assertFalse($dataSaved[$org]->isNewRecord);
            $this->assertEquals($dataSaved[$org]->count_np, 7);
            $this->assertEquals($dataSaved[$org]->count_np_ip, 4);
            $this->assertEquals($dataSaved[$org]->count_np_ul, 3);
            $this->assertEquals($dataSaved[$org]->count_np_provides_reliabe_declare, 2);
            $this->assertEquals($dataSaved[$org]->count_np_provides_not_required, 1);
        }
    }

    /**
     * Тестирование фукнции массового удаления моделей
     * 
     * @see DeclareCampaignUsn::deleteModels()
     */
    public function testDeleteModels()
    {
        $this->clearCache();

        $data = DeclareCampaignUsn::getModels(date('Y'), $this->asDate('2023-05-01'));
        DeclareCampaignUsn::deleteModels($data);
        $dataNew = DeclareCampaignUsn::getModels(date('Y'), $this->asDate('2023-05-01'));
        $this->assertNotEmpty($dataNew);
        $this->assertIsArray($dataNew);
        foreach($dataNew as $item) {
            $this->assertTrue($item->isNewRecord);
        }
    }


    /**
     * Тестирование проверки прав модератора
     * 
     * @see DeclareCampaignUsn::isRoleModerator()
     */
    public function testIsRoleModerator()
    {
        $this->clearCache();

        SecurityHelper::logout();
        $this->assertFalse(DeclareCampaignUsn::isRoleModerator());

        SecurityHelper::login();
        SecurityHelper::revokeRoles();        
        SecurityHelper::createRole(DeclareCampaignUsn::getRoleModerator(), true);
        SecurityHelper::relogin();
        $this->assertTrue(DeclareCampaignUsn::isRoleModerator());
        
        SecurityHelper::revokeRoles();
        SecurityHelper::assignRole('admin');
        SecurityHelper::relogin();
        $this->assertTrue(DeclareCampaignUsn::isRoleModerator());
    }


    /**
     * Удаление кэша
     */
    private function clearCache()
    {
        Yii::$app->cache->delete('declare_campaing_usn_last_data');
    }

    /**
     * Форматирование даты
     * @param string $data дата
     * @return string
     */
    private function asDate($date)
    {
        return Yii::$app->formatter->asDate($date);
    }

    /**
     * Список организаций, которые должны использоваться при загрузке/сохранении
     * 
     * @return array
     */
    private function getOrgs()
    {
        return ArrayHelper::map((new Query())
            ->from('{{%organization}}')
            ->where(['date_end' => null])
            ->andWhere(['not', ['code' => ['8625']]])
            ->andWhere(['like', 'code', '86__', false])
            ->all(), 'code', 'code');
    }


}