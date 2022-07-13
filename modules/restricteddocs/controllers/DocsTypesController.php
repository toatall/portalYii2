<?php

namespace app\modules\restricteddocs\controllers;

use Yii;
use app\modules\restricteddocs\models\RestrictedDocsTypes;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DocsTypesController implements the CRUD actions for RestrictedDocsTypes model.
 */
class DocsTypesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
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
     * Lists all RestrictedDocsTypes models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RestrictedDocsTypes::find(),
        ]);

        Yii::$app->response->format = Response::FORMAT_JSON;
        return [
            'title' => 'Управление организациями',
            'content' => $this->renderAjax('index', [
                'dataProvider' => $dataProvider,
            ]),
        ];
    }

    
    /**
     * Creates a new RestrictedDocsTypes model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RestrictedDocsTypes();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirectAjax(Url::to(['/restricteddocs/docs-types']), '#pjax-restricted-docs-types-index');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RestrictedDocsTypes model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirectAjax(Url::to(['/restricteddocs/docs-types']), '#pjax-restricted-docs-types-index');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing RestrictedDocsTypes model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirectAjax(Url::to(['/restricteddocs/docs-types']), '#pjax-restricted-docs-types-index');
    }

    /**
     * Redirect into the modal dialog
     * @return mixed
     */
    private function redirectAjax($url, $container)
    {              
        return $this->renderAjax('../redirect-pjax', [
            'url' => $url,
            'container' => $container,
        ]);
    }

    /**
     * Finds the RestrictedDocsTypes model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RestrictedDocsTypes the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestrictedDocsTypes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
