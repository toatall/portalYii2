<?php
namespace tests\unit\models\lifehack;

use app\models\lifehack\Lifehack;
use app\models\lifehack\LifehackLike;
use app\tests\fixtures\LifeHackFixture;
use app\tests\fixtures\OrganizationFixture;
use app\tests\fixtures\UserFixture;
use app\tests\unit\helpers\SecurityHelper;
use Faker;
use Yii;
use yii\helpers\FileHelper;

class LifeHackLikesTest extends \Codeception\Test\Unit
{       

    /**
     * @var Faker\Generator
     */
    private $faker; 

    /**
     * @var \UnitTester
     */
    public $tester;    

    public function _fixtures()
    {        
        return [         
            'users' => UserFixture::class,               
            OrganizationFixture::class,
            'lifehacks' => LifeHackFixture::class,         
        ];
    }

    /**
     * {@inheritDoc}
     */
    public function _before()
    {
        $this->faker = Faker\Factory::create();           
    }   


    public function testLike()
    {
        SecurityHelper::login();

        /** @var Lifehack $record */
        $record = $this->tester->grabFixture('lifehacks', 3);
        $likes = $this->generateLikes($record->id);        
       
        $avg = 0;
        foreach($likes as $like) {
            $avg += $like->rate;
        }
        $avg = $avg / count($likes);
        expect($avg)->toEqual($record->getAvg(), 'Проверка функции получения среднего значения лайков');
        expect($likes)->arrayToHaveCount($record->getCountRate(), 'Проверка фцнкции возвращающая количество лайков');
        expect($record->lifehackLike)->notToBeEmpty('Дочерняя модель (лайк) не пуста');

        $likeModel = new LifehackLike([
            'id_lifehack' => $record->id,
            'rate' => 0,
        ]);
        expect($likeModel->save())->toBeFalse('Не сохраняется лайк с 0 значением');

        expect($record->liked())->toBeTrue('Текущий пользователь лайкнул');
        foreach($likes as $like) {
            $like->delete();
        }
        expect($record->liked())->toBeFalse('Текущий пользователь не лайкал');
    }

    /**
     * Генерирование лайков
     * @param int $idModel идентификатор лайфхака     
     * @return LifehackLike[]
     */
    private function generateLikes($idModel)
    {
        $result = [];
        /** @var array $users */
        $users = $this->tester->grabFixture('users');
        foreach($users as $user) {
            SecurityHelper::logout();            
            SecurityHelper::login($user['username']);
            $like = new LifehackLike([
                'id_lifehack' => $idModel,
                'rate' => rand(1, 5),
            ]);
            $result[] = $like;
            expect($like->save())->toBeTrue('Сохранение лайка');
        }     
        return $result;
    }    
    

}