<?php

namespace app\modules\restricteddocs\controllers;

use app\modules\restricteddocs\models\RestrictedDocs;
use yii\data\ActiveDataProvider;
use app\components\Controller;
use Yii;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Response;

/**
 * Default controller for the `restricteddocs` module
 */
class DefaultController extends Controller
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
                        'roles' => ['@'],
                    ],                    
                ],
            ],
        ];
    }
    
    
    /**
     * Renders the index view for the module
     * @return string
     */
    // public function actionIndex($findOrg=null, $findType=null, $searchName=null)
    // {        
    //     $dataProvider = $this->getProvider($findOrg, $findType, $searchName);

    //     return $this->render('index', [
    //         'dataProvider' => $dataProvider,
    //         'findOrg' => $findOrg,
    //         'findType' => $findType,
    //         'searchName' => $searchName,
    //     ]);
    // }

    public function actionIndex()
    {
        $listOrgsGeneral = [];
        $listOrgsOther = [];
        foreach ($this->listOrgs() as $org) {
            if ($org['is_show_result']) {
                $listOrgsGeneral[] = $org;
            }
            else {
                $listOrgsOther[] = $org;
            }
        }

        return $this->render('index',[
            'listOrgsGeneral' => $listOrgsGeneral,
            'listOrgsOther' => $listOrgsOther,      
        ]);
    }

    public function actionGetListTypes()
    {
        $orgs = Yii::$app->request->post('orgs', []);
        $data = $this->listTypesByOrgs($orgs);
        Yii::$app->response->format = Response::FORMAT_JSON;
        return $data ?? [];
    }

    public function actionTable()
    {
        $orgs = Yii::$app->request->post('orgs', []);
        $types = Yii::$app->request->post('types', []);
        return $this->renderAjax('table', [
            'dataProvider' => $this->getProvider($orgs, $types, null),
        ]);
    }

    public function actionT()
    {
        return $this->render('t');
    }

    /**
     * @return \yii\data\ActiveDataProvider
     */
    private function getProvider($orgs, $types, $searchName)
    {
        $query = RestrictedDocs::find()
            ->alias('t')
            ->select('t.*')
            ->leftJoin('{{%restricted_docs_orgs__restricted_docs}} link_orgs', 'link_orgs.id_doc = t.id')
            ->leftJoin('{{%restricted_docs_types__restricted_docs}} link_types', 'link_types.id_doc = t.id')
            ->leftJoin('{{%restricted_docs_orgs}} orgs', 'orgs.id = link_orgs.id_org')
            ->leftJoin('{{%restricted_docs_types}} types', 'types.id = link_types.id_type')
            // ->filterWhere([
            //     'orgs.id' => $findOrg,
            //     'types.id' => $findType,
            // ])
            ->where(['in', 'orgs.id', $orgs])
            ->andWhere(['in', 'types.id', $types]);
            // ->andFilterWhere(['like', 't.name', $searchName]);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);
    }

    private function listOrgs()
    {
        return (new Query())
            ->from('{{%restricted_docs_orgs}}')
            ->select('id, name, is_show_result, text_result')
            ->indexBy('id')
            ->all();
    }

    private function listTypesByOrgs($orgs)
    {
        return (new Query())
            ->from('{{%restricted_docs_types}} t')
            ->select('t.id, t.name')
            ->innerJoin('{{%restricted_docs_types__restricted_docs}} link_rest_types', 'link_rest_types.id_type = t.id')
            ->innerJoin('{{%restricted_docs_orgs__restricted_docs}} link_rest_orgs', 'link_rest_orgs.id_doc = link_rest_types.id_doc')            
            ->innerJoin('{{%restricted_docs_orgs}} orgs', 'orgs.id = link_rest_orgs.id_org')
            ->where(['in', 'orgs.id', $orgs])            
            ->all();
    }
    
    
}
