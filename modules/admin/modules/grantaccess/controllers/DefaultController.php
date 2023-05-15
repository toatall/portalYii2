<?php

namespace app\modules\admin\modules\grantaccess\controllers;

use app\components\Controller;
use app\models\UserSearch;
use app\modules\admin\modules\grantaccess\models\GrantAccessGroup;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `admin` module
 */
class DefaultController extends Controller
{
    
    /**
     * Поведения
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'assign-user' => ['POST'],
                    'revoke-user' => ['POST'],
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
     * Renders the index view for the module
     * @param string $unique
     * @return string
     */
    public function actionIndex(string $unique)
    {
        // поиск по unique
        $model = $this->findModelByUnique($unique);

        // если группы нет, то создаем модель
        // в view будет предложено ее создать
        if ($model === null) {
            $model = new GrantAccessGroup([
                'unique' => $unique,
            ]);            
        }

        // создаем новую группу (если передана форма)
        if ($model->load(\Yii::$app->request->post()) && $model->save()) {}

        // ищем только пользователей входящих в нашу группу
        if ($model !== null) {
            $searchModel = new UserSearch([
                'includeIdGroup' => $model->id,
            ]);           
            $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);            
        }

        return $this->render('index', [
            'unique' => $unique,
            'model' => $model,
            'dataProvider' => $dataProvider ?? null,  
            'searchModel' => $searchModel ?? null,          
        ]);
    }

    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        return $model->delete();
    }

    /**
     * Добавление пользователя в группу
     * @param int $idGroup идентификатор группы
     * @param int $idUser идентификатор пользователя
     * @return string
     */
    public function actionAssignUser($idGroup, $idUser)
    {
        $modelGroup = $this->findModel($idGroup);        
        $modelGroup->assignUser($idUser);
        return 'OK';
    }
    
    /**
     * Удаление пользователя из группы
     * @param int $idGroup идентификатор группы
     * @param int $idUser идентификатор пользователя
     * @return string
     */
    public function actionRevokeUser($idGroup, $idUser)
    {
        $modelGroup = $this->findModel($idGroup);
        $modelGroup->revokeUser($idUser);
        return 'OK';
    }

    /**
     * Поиск группы по полю $unique
     * @param string $unique уникальное имя группы
     * @return GrantAccessGroup|null
     */
    private function findModelByUnique($unique) 
    {
        return GrantAccessGroup::find()
            ->where(['unique' => $unique])
            ->one();
    }

    /**
     * Поиск по идентификатору
     * @param int $id идентификатор пользователя
     * @return GrantAccessGroup|null
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if (($model = GrantAccessGroup::findOne($id)) === null) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
}
