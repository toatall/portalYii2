<?php
namespace tests\unit\models\conference;

use app\models\conference\EventsAll;
use app\tests\fixtures\ConferenceFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use Yii;

class EventsAllTest extends \Codeception\Test\Unit
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

    public function testFind()
    {
        $d1 = Yii::$app->formatter->asDatetime('2023-05-01 00:00:00');
        $d2 = Yii::$app->formatter->asDatetime('2023-05-02 00:00:00');
        $record1 = $this->tester->grabFixture('conferences', 11);
        $record2 = $this->tester->grabFixture('conferences', 21);

        $results = EventsAll::findEvents($d1, $d2)->all();
        expect($results)->arrayToHaveCount(2);
        foreach($results as $result) {
            expect(in_array($result['id'], [$record1['id'], $record2['id']]))->toBeTrue();
        }
    }
    
}