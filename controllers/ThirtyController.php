<?php

namespace app\controllers;

use app\models\Organization;
use app\models\thirty\ThirtyHappyBirthday;
use app\models\thirty\ThirtyOldEmployee;
use app\models\thirty\ThirtyPhotoOld;
use app\models\thirty\ThirtyRadio;
use app\models\thirty\ThirtyRadioComment;
use app\models\thirty\ThirtyThroughTime;
use app\models\thirty\ThirtyVeteran;
use yii\db\Expression;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * ThirtyController
 * @package app\controllers
 */
class ThirtyController extends \yii\web\Controller
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
        $modelThirtyOldEmployee = new ThirtyOldEmployee();

        return $this->render('index', [
            'organizations' => $organizations,
            'modelThirtyOldEmployee' => $modelThirtyOldEmployee->findAll(),
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
        $model = $this->loadModelThirtyOldEmployee($id);

        if (\Yii::$app->request->isAjax) {
            $resultJson = [
                'title' => $model['fio_full'],
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
     * 30 летние сотрудники
     * @return string
     * @throws \yii\web\HttpException
     */
    public function actionHappyBirthday()
    {
        $modelHappyBirthday = new ThirtyHappyBirthday();
        return $this->render('happyBirthday', [
            'model' => $modelHappyBirthday->findAll(),
        ]);
    }

    /**
     * Старые фотки
     * @return false|string|string[]|null
     * @throws \yii\web\HttpException
     */
    public function actionPhotoOld()
    {
        $modelPhotoOld = new ThirtyPhotoOld();
        return $this->render('photoOld', [
            'model' => $modelPhotoOld->findAll(),
        ]);
    }

    /**
     * Сквозь время
     * @return false|string|string[]|null
     * @throws \yii\web\HttpException
     */
    public function actionThroughTime()
    {
        $modelThroghTime = new ThirtyThroughTime();
        return $this->render('throughTime', [
            'model' => $modelThroghTime->findAll(),
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
     * @param $id
     * @return string|string[]|null
     * @throws NotFoundHttpException
     */
    public function actionRadioComment($id)
    {
        $model = $this->loadRadio($id);
        if (\Yii::$app->request->isAjax) {
            $resultJson = [
                'title' => 'Комментарии',
                'content' => $this->renderAjax('radio-comment', [
                    'model' => $model,
                    'id' => $id,
                ]),
            ];
            return Json::encode($resultJson);
        }

        return $this->render('radio-comment', [
            'model' => $model,
            'id' => $id,
        ]);
    }

    /**
     * @param $idRadio
     * @return string|string[]|null
     */
    public function actionRadioCommentIndex($idRadio)
    {
        return $this->renderAjax('radio-comment-index',[
            'id' => $idRadio,
            'query' => $this->loadRadioComments($idRadio),
        ]);
    }

    /**
     * @param $idRadio
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionRadioCommentForm($idRadio)
    {
        $modelRadio = $this->loadRadio($idRadio);

        $model = new ThirtyRadioComment();
        $model->id_radio = $idRadio;
        $model->author = \Yii::$app->user->identity->username_windows;
        if ($model->load(\Yii::$app->request->post())) {
            if ($model->save()) {
                return 'OK';
            }
        }

        return $this->renderAjax('radio-comment-form', [
            'id'=>$idRadio,
            'model'=>$model,
        ]);
    }


    /**
     * Delete comment radio
     * @param int $id
     * @return string
     * @throws BadRequestHttpException
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionRadioCommentDelete($id)
    {
        if (!\Yii::$app->request->isPost) {
            throw new BadRequestHttpException('You need use post-request');
        }
        $model = $this->loadRadioComment($id);
        if (\Yii::$app->user->can('admin') || $model->author == \Yii::$app->user->identity->username) {
            if ($model->delete()) {
                return 'OK';
            }
            echo '<div class="alert alert-danger">Ошибка при удалении!</div>';
        }
        else {
            throw new ForbiddenHttpException();
        }
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
        $model = new ThirtyVeteran();
        return $this->render('veteran', [
            'result' => $model->findAll(),
            'model' => $model,
        ]);
    }


    /**
     * Поиск сотрудников с 30 летним стажем
     * @param $id
     * @return array|null
     * @throws NotFoundHttpException
     * @throws \yii\web\HttpException
     */
    private function loadModelThirtyOldEmployee($id)
    {
        $model = (new ThirtyOldEmployee())->findById($id);
        if ($model==null)
            throw new NotFoundHttpException();
        return $model;
    }

    /**
     * @param $id
     * @return ThirtyRadio|null
     * @throws NotFoundHttpException
     */
    private function loadRadio($id)
    {
        $model = ThirtyRadio::findOne($id);
        if ($model==null) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    /**
     * @param $id
     * @return ThirtyRadioComment
     * @throws NotFoundHttpException
     */
    private function loadRadioComment($id)
    {
        $model = ThirtyRadioComment::findOne($id);
        if ($model==null) {
            throw new NotFoundHttpException();
        }
        return $model;
    }

    /**
     * @param $id
     * @return ThirtyRadioComment[]
     */
    private function loadRadioComments($id)
    {
        $model = ThirtyRadioComment::find()->where(['id_radio' => $id])->all();
        return $model;
    }


}
