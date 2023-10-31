<?php
namespace app\modules\dashboardEcr\controllers;

use app\components\Controller;
use app\modules\dashboardEcr\models\MigrateRegions;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;

class DefaultController extends Controller
{
    
    /**
     * {@inheritDoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'actions' => ['index', 'get-data'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                    [
                        'actions' => ['update'],
                        'allow' => true,
                        'roles' => ['admin'],
                    ],
                ],
            ],
        ];
    }


    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionGetData($type)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $this->getData($type);
    }        

    private function getData($type) 
    {
        $field = $type == 'in' ? 'count_in' : 'count_out';
        $query = (new Query())
            ->select("reg_code, {$field} as count_np")
            ->from('{{%migrate_regions}}')
            ->orderBy([$field => SORT_DESC])
            ->where(['>', $field, 0])
            ->all();
        return ArrayHelper::map($query, 'reg_code', 'count_np');        
    }

    public function actionUpdate()
    {
        $this->titleAjaxResponse = 'Редактирование';

        $models = [];
        foreach($this->getRegions() as $region) {            
            $models[$region['reg_code']] = MigrateRegions::findOrCreate($region['reg_code'], $region['reg_name']);
        }

        if (MigrateRegions::loadMultiple($models, Yii::$app->request->post()) && 
            MigrateRegions::validateMultiple($models)) {
            foreach($models as $model) {
                $model->save();
            }
            Yii::$app->session->setFlash('save-migrate-regions');
        }

        return $this->render('form', ['models' => $models]);
    }

    private function getRegions()
    {
        return (new Query())
            ->from('{{%dictionary_regions}}')
            ->orderBy('reg_code')
            ->all();
    }


}