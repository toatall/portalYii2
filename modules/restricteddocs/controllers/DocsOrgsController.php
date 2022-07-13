<?php

namespace app\modules\restricteddocs\controllers;

use Yii;
use app\modules\restricteddocs\models\RestrictedDocsOrgs;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * DocsOrgsController implements the CRUD actions for RestrictedDocsOrgs model.
 */
class DocsOrgsController extends Controller
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
     * Lists all RestrictedDocsOrgs models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => RestrictedDocsOrgs::find(),
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
     * Creates a new RestrictedDocsOrgs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new RestrictedDocsOrgs();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirectAjax(Url::to(['/restricteddocs/docs-orgs']), '#pjax-restricted-docs-orgs-index');           
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing RestrictedDocsOrgs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirectAjax(Url::to(['/restricteddocs/docs-orgs']), '#pjax-restricted-docs-orgs-index');
        }

        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
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
     * Deletes an existing RestrictedDocsOrgs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        return $this->redirectAjax(Url::to(['/restricteddocs/docs-orgs']), '#pjax-restricted-docs-orgs-index');        
    }

    /**
     * Finds the RestrictedDocsOrgs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return RestrictedDocsOrgs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = RestrictedDocsOrgs::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
