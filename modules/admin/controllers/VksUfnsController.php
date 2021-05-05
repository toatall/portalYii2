<?php

namespace app\modules\admin\controllers;

use app\models\Access;
use app\models\Tree;
use Yii;
use app\models\conference\VksUfns;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * VksUfnsController implements the CRUD actions for VksUfns model.
 */
class VksUfnsController extends Controller
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
                        'matchCallback' => function ($rule, $action) {
                            if (($model = Tree::findOne(['module' => VksUfns::getModule()])) == null) {
                                throw new NotFoundHttpException('Не найден узел с модулем "' . VksUfns::getModule() . '"');
                            }
                            if (Access::checkAccessUserForTree($model->id)) {
                                return true;
                            }
                            return false;
                        }
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all VksUfns models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => VksUfns::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single VksUfns model.
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
     * Creates a new VksUfns model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws \yii\base\InvalidConfigException
     */
    public function actionCreate()
    {
        $model = new VksUfns();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->notifyEmail('ВКС с УФНС');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing VksUfns model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \yii\base\InvalidConfigException
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        
        if (\Yii::$app->request->isAjax && \Yii::$app->request->post('hasEditable')) { 
            $post = Yii::$app->request->post();
            $model->load($post, 'Conference');            
            $output = null;                                     
            $resultSave = $model->save();
            
            if (isset($post['Conference']['arrPlace'])) {
                $output = $model->place;                
            }   
            
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return [       
                'output' => $output,
                'message' => $resultSave ? '' : 'Ошибка при сохранении',
            ];
        }
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $model->notifyEmail('ВКС с УФНС');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing VksUfns model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the VksUfns model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return VksUfns the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = VksUfns::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return Tree
     * @throws NotFoundHttpException
     * @throws ForbiddenHttpException
     */
    protected function tree($rule, $action)
    {
        if (($model = Tree::findOne(['module' => VksUfns::getModule()])) == null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        if (!Access::checkAccessUserForTree($model->id)) {
            throw new ForbiddenHttpException('Доступ запрещен');
        }

        return true;
    }
}
