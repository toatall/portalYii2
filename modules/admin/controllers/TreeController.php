<?php

namespace app\modules\admin\controllers;

use Yii;
use app\modules\admin\models\tree\Tree;
use yii\db\Expression;
use yii\filters\AccessControl;
use app\components\Controller;
use app\modules\admin\models\tree\TreeAccess;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use app\modules\admin\models\tree\TreeBuild;

/**
 * TreeController implements the CRUD actions for Tree model.
 */
class TreeController extends Controller
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
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {        
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionTree()
    {        
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Tree::generateJsonTree(TreeBuild::buildingTree());
    }

    /**
     * Displays a single Tree model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Tree model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idParent=null)
    {
        $model = new Tree();
        $model->module = $model->getParamDefaultModule();
        $model->useParentRight = 1;
        if ($idParent) {
            $model->id_parent = $idParent;
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {        
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Tree model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $parentId = $model->id_parent;

        if ($model->load(Yii::$app->request->post())) {
            if ($model->id == $model->id_parent) {
                $model->id_parent = $parentId;
            }
            if ($model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }
                
        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Tree model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->user->can('admin')) {
            $model->delete();
        }
        else {
            $model->date_delete = new Expression('getdate()');
            $model->save();
        }

        return $this->redirect(['index']);        
    }

    /**
     * Finds the Tree model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tree the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     * @throws ForbiddenHttpException
     */
    protected function findModel($id)
    {
        if (($model = Tree::findOne(['id' => $id])) !== null) {
            if (!TreeAccess::isAccessToTreeNode($model->id)) {
                throw new ForbiddenHttpException('Доступ запрещен');
            }
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
