<?php

namespace app\modules\test\controllers;

use app\models\User;
use app\modules\test\models\Test;
use Yii;
use app\modules\test\models\TestQuestion;
use yii\data\ActiveDataProvider;
use yii\data\ArrayDataProvider;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;

class AccessController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Просмотр пользователей с доступом к 
     * статистике всех тестов
     * @param integer $idTest
     */
    public function actionIndex($idTest=0)
    {
        $query = $this->findUsers($idTest);
        return $this->render('index', [
            'dataProvider' => new ArrayDataProvider([
                'allModels' => $query->all(),
                'pagination' => false,                
            ]),
            'idTest' => $idTest,
            'modelTest' => $this->findTest($idTest),
        ]);
    }

    /**
     * Добавление нового пользователя
     * @param integer $idTest
     */
    public function actionAdd($idTest=0)
    {
        $searchModel = new User();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, $this->getAssigmentUserByTest($idTest));
        
        if (($userId = Yii::$app->request->post('user_id')) !== null) {
            $this->assetUser($idTest, $userId);
            return $this->redirect(['/test/access/index', 'idTest'=>$idTest]);
        }

        return $this->render('add', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'idTest' => $idTest,
        ]);
    }

    public function actionDelete($id, $idTest)
    {
        // удаление из таблицы {{%access_test_statistic}}
        Yii::$app->db->createCommand()
            ->delete('{{%access_test_statistic}}', [
                'id_user' => $id,
                'id_test' => $idTest,
            ])
            ->execute();
        
        // аунлирование разрешения у пользователя
        $auth = Yii::$app->authManager;
        $permission = $auth->getPermission('test-statistic');        
        $auth->revoke($permission, $id);        

        return $this->redirect(['index', 'idTest'=>$idTest]);
    }

    /**
     * Выбрать идентификаторы пользователей, подключенных к указанному тесту
     * @param integer $idTest
     */
    private function getAssigmentUserByTest($idTest)
    {
        $query = (new Query())
            ->from('{{%access_test_statistic}}')
            ->where(['id_test' => $idTest])
            ->all();
        if ($query === null) {
            return null;
        } 
        return implode(',', ArrayHelper::map($query, 'id_user', 'id_user'));
    }


    /**
     * Поиск пользователей
     * @param integer $id - идентификатор теста, если указан, то показать только пользователей,
     * привязанных к данному тесту, если не указан, то показать пользователей имеющих
     * доступ ко всем тестам
     * @return Query
     */
    private function findUsers($id=null)
    {
        $query = (new Query())
            ->from('{{%access_test_statistic}} t')
            ->leftJoin('{{%test}} test', 'test.id=t.id_test')
            ->innerJoin('{{%user}} u', 'u.id=t.id_user')            
            ->leftJoin('{{%auth_assignment}} assigment', 'u.id=assigment.user_id')            
            ->where(['or', ['assigment.item_name' => null], ['assigment.item_name' => 'test-statistic']])
            ->select('t.id, t.id_test, u.id as id_user, u.username, t.date_create, u.fio, assigment.item_name, test.name as test_name');
        if ($id !== null) {
            $query->andWhere('t.id_test=:id_test', [':id_test' => $id]);
        }
        else {
            $query->andWhere(['t.id_test' => 0]);
        }                
        return $query;
    }

    /**
     * @param integer $id
     * @return Test|null
     */
    private function findTest($id)
    {
        return Test::findOne($id);
    }

    /**
     * Применение прав для пользователя
     * @param integer $idTest
     * @param integer $idUser
     */
    private function assetUser($idTest, $idUser)
    {
        $auth = Yii::$app->authManager;
        
        // проверка есть ли пользователь в разрешениях
        // если нет, то добавляем разрешение пользователю            
        if (!$auth->getAssignment('test-statistic', $idUser)) {
            $permission = $auth->getPermission('test-statistic');
            $auth->assign($permission, $idUser);             
        }
           
        // добавление в таблицу access_test_statistic
        if (!((new Query())
            ->from('{{%access_test_statistic}}')
            ->where([
                'id_test' => $idTest,
                'id_user' => $idUser,
            ])->exists())) {
                Yii::$app->db->createCommand()
                    ->insert('{{%access_test_statistic}}', [
                        'id_test' => $idTest,
                        'id_user' => $idUser,
                        'date_create' => Yii::$app->formatter->asDatetime('now'),
                    ])->execute();
            }
    }




}
