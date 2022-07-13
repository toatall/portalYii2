<?php

namespace app\modules\restricteddocs\controllers;

use app\modules\restricteddocs\models\RestrictedDocs;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\filters\AccessControl;

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
    public function actionIndex($findOrg=null, $findType=null, $searchName=null)
    {        
        $dataProvider = $this->getProvider($findOrg, $findType, $searchName);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'findOrg' => $findOrg,
            'findType' => $findType,
            'searchName' => $searchName,
        ]);
    }

    /**
     * @return \yii\data\ActiveDataProvider
     */
    private function getProvider($findOrg, $findType, $searchName)
    {
        $query = RestrictedDocs::find()
            ->alias('t')
            ->select('t.*')
            ->leftJoin('{{%restricted_docs_orgs__restricted_docs}} link_orgs', 'link_orgs.id_doc = t.id')
            ->leftJoin('{{%restricted_docs_types__restricted_docs}} link_types', 'link_types.id_doc = t.id')
            ->leftJoin('{{%restricted_docs_orgs}} orgs', 'orgs.id = link_orgs.id_org')
            ->leftJoin('{{%restricted_docs_types}} types', 'types.id = link_types.id_type')
            ->filterWhere([
                'orgs.id' => $findOrg,
                'types.id' => $findType,
            ])
            ->andFilterWhere(['like', 't.name', $searchName]);

        return new ActiveDataProvider([
            'query' => $query,
            'sort' => false,
        ]);
    }    
    
    
}
