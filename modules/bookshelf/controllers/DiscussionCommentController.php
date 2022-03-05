<?php

namespace app\modules\bookshelf\controllers;

use Yii;
use app\modules\bookshelf\models\BookShelfDiscussionComment;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;

/**
 * DiscussionCommentController implements the CRUD actions for BookShelfDiscussionComment model.
 */
class DiscussionCommentController extends Controller
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
                        'roles' => ['@'],
                    ],                    
                ],
            ],
        ];
    }

    /**
     * Lists all BookShelfDiscussionComment models.
     * @return mixed
     */
    public function actionIndex($idDiscussion)
    {
        $dataProvider = new ActiveDataProvider([
            'query' => BookShelfDiscussionComment::find()->where([
                'id_book_shelf_discussion' => $idDiscussion,
            ]),
        ]);

        return $this->renderAjax('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single BookShelfDiscussionComment model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new BookShelfDiscussionComment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idDiscussion)
    {
        $model = new BookShelfDiscussionComment();
        $model->id_book_shelf_discussion = $idDiscussion;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return 'OK';
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing BookShelfDiscussionComment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        if (!$model->isEditor()) {
            throw new ForbiddenHttpException();
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing BookShelfDiscussionComment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if ($model->isEditor()) {
            $model->delete();
        }
        else {
            throw new ForbiddenHttpException();
        }      
    }
        

    /**
     * Finds the BookShelfDiscussionComment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return BookShelfDiscussionComment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = BookShelfDiscussionComment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
