<?php

namespace app\modules\rookie\modules\tiktok\controllers;

use Yii;
use app\modules\rookie\modules\tiktok\models\Tiktok;
use app\modules\rookie\modules\tiktok\models\TiktokVote;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * DefaultController implements the CRUD actions for Tiktok model.
 */
class DefaultController extends Controller
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
                        'actions' => ['index', 'view'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all Tiktok models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Tiktok::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tiktok model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
                
        $modelVote = new TiktokVote();
        $modelVote->id_tiktok = $id;
        if ($modelVote->load(Yii::$app->request->post()) && $modelVote->save()) {
            $model = $this->findModel($id);
        }

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;    
            return [
                'title' => 'Ролик от ' . $model->departmentModel->getConcatened(),
                'content' => $this->renderAjax('view', [
                    'model' => $model,
                    'modelVote' => $modelVote,
                ]),
            ];
        }
        return $this->render('view', [
            'model' => $model,
            'modelVote' => $modelVote,
        ]);
    }

    /**
     * Creates a new Tiktok model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Tiktok();

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['content' => 'OK'];
        }

        return [
            'title' => "Add tiktok's video",
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Updates an existing Tiktok model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return ['content' => 'OK'];
        }

        return [
            'title' => "Update the video from " . $model->departmentModel->getConcatened(),
            'content' => $this->renderAjax('_form', [
                'model' => $model,
            ]),
        ];
    }

    /**
     * Deletes an existing Tiktok model.
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

    public function actionVote($id)
    {
        $modelTikTok = $this->findModel($id);
        
        if ($modelTikTok->canVote()) {
            $model = new TiktokVote();
            $model->id_tiktok = $id;

            if (!($model->load(Yii::$app->request->post()) && $model->save())) {
                return $this->renderAjax('vote/_form', [
                    'model' => $model,
                ]);
            }
        }

        // return $this->renderAjax('vote/_view', [
        //     'model' => $modelTikTok,
        // ]);        
        return '<div class="alert alert-success alert-out" style="transition: all 3s; transition-delay: 1s;">Спасибо!</div>';
    }

    /**
     * Finds the Tiktok model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tiktok the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tiktok::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
