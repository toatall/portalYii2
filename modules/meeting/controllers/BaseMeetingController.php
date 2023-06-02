<?php

namespace app\modules\meeting\controllers;

use Yii;
use app\components\Controller;
use app\modules\meeting\models\Meeting;
use app\modules\meeting\models\search\MeetingSearch;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

/**
 * @author toatall
 */
abstract class BaseMeetingController extends Controller
{

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['admin', $this->roleEditor()],
                    ],
                ],
            ],
        ];
    }   

    /**
     * Создание модели поиска мероприятий
     * 
     * @return MeetingSearch
     */
    abstract protected function createNewSearchModel();

    /**
     * Класс модели (для создания, поиска)
     *  
     * @return string
     */
    abstract protected function getModelClass(): string;

    /**
     * Роль редактора
     * 
     * @return string
     */
    abstract protected function roleEditor(): string;
    
    /**
     * Список мероприятий
     * 
     * @return mixed
     */
    public function actionIndex()
    {        
        $searchModel = $this->createNewSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Просмотр подробных сведений ВКС
     * 
     * @param int $id
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Создание новой записи ВКС 
     * 
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ($this->getModelClass());
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Редактирование мероприятия
     * 
     * @param int $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);       

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }    

    /**
     * Удаление мероприятия
     * 
     * @param int $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (Yii::$app->user->can('admin')) {
            return $model->delete();
        }
        else {
            $model->date_delete = time();
            return $model->save(false, ['date_delete']);
        }
    }

    /**
     * @return Meeting|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = $this->getModelClass()::findOne(['id' => $id, 'date_delete' => null])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException();
    }

}