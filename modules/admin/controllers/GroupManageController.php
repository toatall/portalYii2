<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Group;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * GroupManageController implements the CRUD actions for Group model.
 */
class GroupManageController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],                 
                ],
            ],
        ];
    }

    /**
     * Включение пользователя в группу
     * @param int $idGroup
     * @param int $idUser
     * @return string
     */
    public function actionAdd(int $idGroup, int $idUser)
    {
        $modelGroup = $this->findModel($idGroup);
        if ($modelGroup->assetUser($idUser)) {
            return "OK";
        }
    }

    /**
     * Исключение пользователя из группы
     * @param int $idGroup
     * @param int $idUser
     * @return string
     */
    public function actionDelete(int $idGroup, int $idUser)
    {
        $modelGroup = $this->findModel($idGroup);
        if ($modelGroup->revokeUser($idUser)) {
            return "OK";
        }
    }


    /**
     * Finds the Group model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Group the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Group::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
