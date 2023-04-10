<?php
namespace app\modules\project\modules\thirty\controllers;

use app\components\Controller;
use app\models\Organization;
use app\modules\project\modules\thirty\models\ThirtyHappyBirthday;
use app\modules\project\modules\thirty\models\ThirtyOldEmployee;
use app\modules\project\modules\thirty\models\ThirtyPhotoOld;
use app\modules\project\modules\thirty\models\ThirtyRadio;
use app\modules\project\modules\thirty\models\ThirtyThroughTime;
use app\modules\project\modules\thirty\models\ThirtyVeteran;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\NotFoundHttpException;

class DefaultController extends Controller
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
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Главная страница
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionIndex()
    {
        $organizations = Organization::getDropDownList();
        $modelThirtyOldEmployee = ThirtyOldEmployee::find()->all();

        return $this->render('index', [
            'organizations' => $organizations,
            'modelThirtyOldEmployee' => $modelThirtyOldEmployee,
        ]);
    }

    /**
     * 30 лет стажа
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     */
    public function action30YearView($id)
    {        
        $model = $this->findModel30Year($id);

        if (\Yii::$app->request->isAjax) {
            $resultJson = [
                'title' => $model->fio_full,
                'content' => $this->renderAjax('viewThirtyOldEmployee', [
                    'model' => $model,
                ]),
            ];
            return Json::encode($resultJson);
        }

        return $this->render('viewThirtyOldEmployee', [
            'model' => $model,
        ]);
    }

    /**
     * Поиск записи в модели ThirtyOldEmployee
     * с идентификатором $id
     * @param $id идентифиактор записи
     * @return ThirtyOldEmployee
     */
    protected function findModel30Year($id)
    {
        if (($model = ThirtyOldEmployee::find()
                ->where(fn($item) => $item['id'] == $id)
                ->one()) === null) {
            throw new NotFoundHttpException();
        }        
        return $model;
    }

    /**
     * 30 летние сотрудники
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionHappyBirthday()
    {        
        return $this->render('happyBirthday', [
            'data' => ThirtyHappyBirthday::find()
                ->index()
                ->group('org_code')
                ->order('org_code')
                ->all(),     
            'orgs' => Organization::getDropDownList(false, false, false),
        ]);
    }

    /**
     * Старые фотки
     * @return false|string|string[]|null
     * @throws \yii\web\HttpException
     */
    public function actionPhotoOld()
    {        
        return $this->render('photoOld', [
            'data' => ThirtyPhotoOld::find()                
                ->order('org_code')
                ->all(),
            'orgs' => Organization::getDropDownList(false, false, false),
        ]);
    }

    /**
     * Сквозь время
     * @return false|string|string[]|null
     * @throws \yii\web\HttpException
     */
    public function actionThroughTime()
    {        
        return $this->render('throughTime', [
            'model' => ThirtyThroughTime::find()
                ->order('org_code')
                ->all(),
        ]);
    }

    /**
     * Радио эфиры
     * @return false|string|string[]|null
     */
    public function actionRadio()
    {
        $model = ThirtyRadio::find()->all();
        return $this->render('radio', [
            'model' => $model,
        ]);
    }    

    /**
     * Лайк
     * @param $id
     * @return int|string|void
     * @throws \yii\db\Exception
     */
    public function actionRadioLike($id)
    {
        if (\Yii::$app->user->isGuest) {
            return;
        }

        if (!(new Query())
            ->from('{{%thirty_radio_like}}')
            ->where([
                'id_radio' => $id,
                'username' => \Yii::$app->user->identity->username,
            ])
            ->exists()) {

            // insert
            \Yii::$app->db->createCommand()
                ->insert('{{%thirty_radio_like}}', [
                    'id_radio' => $id,
                    'username' => \Yii::$app->user->identity->username,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                    'date_create' => new Expression('getdate()'),
                ])
                ->execute();
        }
        else {
            \Yii::$app->db->createCommand()
                ->delete('{{%thirty_radio_like}}', [
                    'id_radio' => $id,
                    'username' => \Yii::$app->user->identity->username,
                ])
                ->execute();
        }

        // update count
        $query = \Yii::$app->db->createCommand("
                update {{%thirty_radio}}
                set count_like = (select count(id) from {{%thirty_radio_like}} where id_radio = :id1)
                where id=:id2
            ");
        $query->bindParam(':id1', $id);
        $query->bindParam(':id2', $id);
        $query->execute();

        // count like
        return (new Query())
            ->from('{{%thirty_radio_like}}')
            ->where([
                'id_radio' => $id,
            ])
            ->count();
    }

    /**
     * Пометка о просмотре радио-эфира
     * @param $id
     * @return int|string|void
     * @throws \yii\db\Exception
     */
    public function actionRadioView($id)
    {
        if (\Yii::$app->user->isGuest) {
            return;
        }

        \Yii::$app->db->createCommand()
            ->insert('{{%thirty_radio_visit}}', [
                'id_radio' => $id,
                'username' => \Yii::$app->user->identity->username,
                'ip_address' => $_SERVER['REMOTE_ADDR'],
            ])
            ->execute();

        // update count
        $query = \Yii::$app->db->createCommand("
            update {{%thirty_radio}}
            set count_view = (select count(id) from {{%thirty_radio_visit}} where id_radio = :id1)
            where id=:id2
        ");
        $query->bindParam(':id1', $id);
        $query->bindParam(':id2', $id);
        $query->execute();

        // count views
        return (new Query())
            ->from('{{%thirty_radio_visit}}')
            ->where([
                'id_radio' => $id,
            ])
            ->count();
    }

    /**
     * Эмблема ФНС России
     * @return string
     */
    public function actionEmblem()
    {
        if (\Yii::$app->request->isAjax) {
            $resultJson = [
                'title' => 'Геральдический знак',
                'content' => $this->renderAjax('emblem', []),
            ];
            return Json::encode($resultJson);
        }
        return $this->render('emblem');
    }
    
    /**
     * Электронная книга
     * @return string
     */
    public function actionBook()
    {
        if (\Yii::$app->request->isAjax) {
            $resultJson = [
                'title' => 'Юбилейная книга',
                'content' => $this->renderAjax('book', []),
            ];
            return Json::encode($resultJson);
        }
        return $this->render('book'); 
    }
    
    public function actionViewBook()
    {
        return $this->renderPartial('viewBook');
    }

    /**
     * Гинм
     */
    public function actionAnthem()
    {
        if (\Yii::$app->request->isAjax) {
            $resultJson = [
                'title' => 'Гимн налоговых органов Югры',
                'content' => $this->renderAjax('anthem', []),
            ];
            return Json::encode($resultJson);
        }
        return $this->render('anthem');
    }

    /**
     * Видео-открытки
     * @return string
     */
    public function actionVideoCard()
    {
        return $this->render('videoCard');
    }

    /**
     * Поздравление ветеранов
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionVeteran()
    {        
        return $this->render('veteran', [
            'data' => ThirtyVeteran::find()
                ->order('org_code')
                ->group('org_code')
                ->all(),
            'orgs' => Organization::getDropDownList(false, false, false),
        ]);
    }  
   
}