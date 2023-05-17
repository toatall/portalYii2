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
     * Главная страница
     * 
     * Форма создания/редактирования группы
     * С использованием ajax подгружаются 
     * - пользователи входящие в группу
     * @see self::actionUsers()
     * - группы ActiveDirectory, которые содержатся у пользователей
     * @see AdGroupController::index()
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
        
        return $this->render('index', [
            'unique' => $unique,
            'model' => $model,              
        ]);
    }

    /**
     * Список пользователей, входящих в группу
     * @param string $unique
     * @return mixed
     */
    public function actionUsers($unique)
    {       
        $modelGroup = $this->findModelByUnique($unique);
        if ($modelGroup === null) {
            return '';
        }
        $searchModel = new UserSearch([
            'includeIdGroup' => $modelGroup->id,
        ]);
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams); 
        return $this->renderAjax('gridUsers', [
            'unique' => $unique,
            'idGroup' => $modelGroup->id,
            'dataProvider' => $dataProvider ?? null,
            'searchModel' => $searchModel ?? null,
        ]);
    }

    /**
     * Удаление группы
     * @param int $id идентифиактор группы
     * @return mixed
     */
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
