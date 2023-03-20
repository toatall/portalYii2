<?php

namespace app\controllers;

use Yii;
use app\models\Comment;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\ForbiddenHttpException;
use yii\web\Response;

/**
 * CommentController implements the CRUD actions for Comment model.
 */
class CommentController extends Controller
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
     * Lists all Comment models.
     * @param string $hash
     * @param string @url
     * @return mixed
     */
    public function actionIndex($hash, $url, $title, $modelName, $modelId)
    {      
        if (Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [        
                'hash' => $hash,
                'url' => $url,
                'title' => $title,  
                'modelName' => $modelName,
                'modelId' => $modelId,             
            ]);
        }        
    }

    /**
     * @param string $hash
     * @param string $url
     * @return mixed
     */
    public function actionComments($hash, $url, $modelName, $modelId)
    {        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [            
            'content' => $this->renderAjax('comments', [                
                'comments' => Comment::getComments($hash),
                'hash' => $hash,
                'url' => $url,       
                'modelName' => $modelName,
                'modelId' => $modelId,        
            ]),
        ];
    }

    /**
     * Displays a single Comment model.
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
     * Creates a new Comment model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($hash, $url, $container, $modelName, $modelId, $idParent=null)
    {
        $model = new Comment();
        $model->model_name = $modelName;
        $model->model_id = $modelId;

        // если указан родительский документ
        if ($idParent !== null) {
            // ищем этот документ
            $modelParent = Comment::findOne($idParent);
            if ($modelParent !== null) {
                // если у этого документа тоже есть родительский, то ставим этот родительский
                if ($modelParent->id_parent != null) {
                    $model->id_parent = $modelParent->id_parent;
                }
                else {
                    $model->id_parent = $modelParent->id;
                }
                $model->id_reply = $modelParent->id;
            }
        }
        
        $model->url = $url;
        $model->bind_hash = $hash;

        $resultSave = false;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {            
            $resultSave = true;
            $model = new Comment();
        }

        if ($resultSave) {
            $model->text = null;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'resultSave' => $resultSave,
            'content' => $this->renderAjax('_form', [
                'model' => $model,
                'textPlaceholder' => 'Написать комментарий...',
                'hash' => $hash,
                'url' => $url,
                'idParent' => $idParent,
                'idContainer' => $container,
            ]),
        ];        
    }

    /**
     * Updates an existing Comment model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $container)
    {
        $model = $this->findModel($id);
        $this->checkAutorize($model);
        $resultSave = false;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $resultSave = true;
        }

        if ($resultSave) {
            $model->text = null;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'resultSave' => $resultSave,
            'content' => $this->renderAjax('_form', [
                'model' => $model,
                'textPlaceholder' => 'Написать комментарий...',
                'hash' => $model->bind_hash,
                'url' => $model->url,
                'idParent' => $model->id_parent,
                'idContainer' => $container,
            ]),
        ];      
    }

    /**
     * Deletes an existing Comment model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {       
        $model = $this->findModel($id);
        $this->checkAutorize($model);
        $model->date_delete = new Expression('getdate()');
        $resultDeleted = $model->save();
        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'resultDeleted' => $resultDeleted,            
        ];   
    }

    /**
     * Finds the Comment model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Comment the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Comment::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param Comment $model
     */
    private function checkAutorize($model)
    {
        if (!$model->isAuthor() && !\Yii::$app->user->can('admin')) {
            throw new ForbiddenHttpException('Доступ запрещен');
        }
    }
}
