<?php

namespace app\controllers;

use app\models\DeclareCampaignUsn;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use app\components\Controller;
use app\models\DeclareCampaignUsnChart;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\BadRequestHttpException;
use yii\web\Response;

/**
 * DeclareCampaignUsnController implements the CRUD actions for DeclareCampaignUsn model.
 */
class DeclareCampaignUsnController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
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
            ],            
        );
    }

    /**
     * Lists all DeclareCampaignUsn models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $models = DeclareCampaignUsn::findWithLastDate();

        return $this->render('index', [
            'models' => $models,
        ]);
    }

    /**
     * Change DeclareCampaignUsn models.
     * @return string|\yii\web\Response
     */
    public function actionChange()
    {
        $model = new DeclareCampaignUsn();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('change', [
            'model' => $model,
        ]);
    }

    /**
     * Форма сохранения данных по организациям, 
     * отчетному году, отченой дате
     * @return string
     */
    public function actionForm()
    {
        $date = Yii::$app->request->post('date');
        $year = Yii::$app->request->post('year');
        $delete = Yii::$app->request->post('delete');
        if (!$date || !$year) {
            throw new BadRequestHttpException();
        }
        
        // поиск моделей
        $models = DeclareCampaignUsn::getModels($year, $date);

        if ($delete) {
            DeclareCampaignUsn::deleteModels($models);
            // полчение новых моделей
            $models = DeclareCampaignUsn::getModels($year, $date);
        }

        if (Model::loadMultiple($models, Yii::$app->request->post())) {
            if (DeclareCampaignUsn::saveModels($models)) {
                Yii::$app->session->setFlash('success', 'Данные успешно сохранены!');
            }
            else {
                Yii::$app->session->setFlash('danger', 'При сохранении произошла ошибка!');
            }
        }

        return $this->renderAjax('_form', [
            'models' => $models,
            'year' => $year,
            'date' => $date,
        ]);
    }

    /**
     * Данные для графика
     * @param string $org
     */
    public function actionDataChart($org) 
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return DeclareCampaignUsnChart::generateDataToChart($org);
    }
    

    /**
     * Deletes an existing DeclareCampaignUsn model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the DeclareCampaignUsn model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return DeclareCampaignUsn the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DeclareCampaignUsn::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
