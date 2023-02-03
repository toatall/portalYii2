<?php

namespace app\controllers;

use app\models\lifehack\Lifehack;
use app\models\lifehack\LifehackLike;
use app\models\lifehack\LifehackSearch;
use app\models\lifehack\LifehackTags;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\web\UploadedFile;
use app\components\Controller;

/**
 * Лайфкаки (проект от Гуляевой С.В.)
 * @author toatall
 */
class LifehackController extends Controller
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
                    'delete-tag' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['admin', 'lifehack-editor'],
                    ],
                    [
                        'actions' => ['index-tags', 'create-tag', 'update-tag', 'delete-tag'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /******************** <LIFEHACKS> ********************/ 

    /**
     * Главная страница
     * @return string
     */
    public function actionIndex($tag=null)
    {
        $searchModel = new LifehackSearch();        
        $searchModel->tags = $tag;        
        $dataProvider = $searchModel->search(\Yii::$app->request->queryParams);
        return $this->render('index',[
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'tag' => $tag,
        ]);
    }

    /**
     * Добавление лайфхака
     * @return string
     */
    public function actionCreate()
    {
        $model = new Lifehack();
        if (!Yii::$app->user->can('admin')) {
            $model->org_code = Yii::$app->user->identity->current_organization;
        }
        
        if ($model->load(Yii::$app->request->post())) {  
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            if ($model->save()) {
                return $this->renderAjax('success', [
                    'message' => 'Данные успешно сохранены!',
                ]);
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Добавление лайфхака',
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Изменение лайфхака
     * @return string
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!Yii::$app->user->can('admin')) {
            $model->org_code = Yii::$app->user->identity->current_organization;
        }
        
        if ($model->load(Yii::$app->request->post())) {  
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            if ($model->save()) {
                return $this->renderAjax('success', [
                    'message' => 'Данные успешно сохранены!',
                ]);
            }
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Изменение лайфхака',
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Просмотр лайфхака
     * @return string
     */
    public function actionView($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = $this->findModel($id);

        $modelLike = $model->lifehackLike;
        if ($modelLike == null) {
            $modelLike = new LifehackLike();
            $modelLike->id_lifehack = $id;
            // $modelLike->rate = 5;
        }
        else {
            // $modelLike->rate = 0;
        }
        if ($modelLike->load(Yii::$app->request->post()) && $modelLike->save()) {  
            $model = $this->findModel($id);
        }

        return [
            'title' => $model->title,
            'content' => $this->renderAjax('view', [
                'model' => $model,
                'modelLike' => $modelLike,
            ]),
        ];
    }

    /**
     * Deletes an existing CalendarTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();       
        return $this->redirect(['index']);
    }

    /**
     * @param $id
     * @return Lifehack|array|null
     * @throws NotFoundHttpException
     */
    protected function findModel($id)
    {
        if (($model = Lifehack::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    /******************** </LIFEHACKS> ********************/ 





    /******************** <TAGS> ********************/ 

    /**
     * Управление тегами
     * @return string
     */
    public function actionIndexTags()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Тэги для лайфхаков',
            'content' => $this->renderAjax('tags/index', [
                'dataProvider' => new ActiveDataProvider([
                    'query' => LifehackTags::find(),
                ]),
            ])
        ];
    }

    /**
     * Добавление тега
     * @return string
     */
    public function actionCreateTag()
    {
        $model = new LifehackTags();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirectPjax(['lifehack/index-tags']);
        }

        return $this->renderAjax('tags/_form', [
            'model' => $model,
        ]);
    }

    /**
     * Изменение тега
     * @return string
     */
    public function actionUpdateTag($id)
    {
        $model = $this->findModelTag($id);
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            return $this->redirectPjax(['lifehack/index-tags']);
        }

        return $this->renderAjax('tags/_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing CalendarTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDeleteTag($id)
    {
        $this->findModelTag($id)->delete();
        return $this->redirectPjax(['lifehack/index-tags']);
    }

    /**
     * @param $id
     * @return LifehackTags|array|null
     * @throws NotFoundHttpException
     */
    protected function findModelTag($id)
    {
        if (($model = LifehackTags::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /******************** </TAGS> ********************/ 


    /**
     * Перенаправление с учетом pjax (без перегрузки страницы)
     * @return string
     */
    private function redirectPjax($url)
    {
        return $this->renderAjax('@app/views/redirect-pjax', [
            'url' => Url::to($url),
            'container' => '#pjax-lifehack-index-tags',
        ]);
    }
}
