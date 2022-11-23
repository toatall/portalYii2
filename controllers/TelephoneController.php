<?php

namespace app\controllers;

use app\models\telephone\TelephoneSearch;
use yii\filters\AccessControl;
use app\components\Controller;
use app\models\Telephone;
use app\models\telephone\TelephoneSOAP;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Response;

class TelephoneController extends Controller
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
                        'roles' => ['@', '?'],
                    ],
                ],
            ],           
        ];
    }

    
    /**
     * Главная страница
     * @return string
     */
    public function actionIndex($unidPerson=null, $unidOrg=null)
    {            
        $telephoneSearch = new TelephoneSOAP();
        
        $dataProvider = new ActiveDataProvider([
            'query' => Telephone::find()->orderBy('id_organization asc'),
        ]);

        return $this->render('index', [            
            // tab1
            'organizationDataProvider' => $telephoneSearch->getStructureOrg($unidOrg),
            'organizationUnid' => $unidOrg,           
            'unidPerson' => $unidPerson,
            'organization' => $telephoneSearch->getDocByUnid($unidOrg),
             // tab2
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Поиск человека
     */
    public function actionFind($term)
    {
        $telephoneSearch = new TelephoneSOAP();
        $data = $telephoneSearch->search($term);
             
        if (isset($data['type'])) {
            $data = [$data];
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($data && is_array($data)) {
            $res = [];
            foreach ($data as $item) {                
                if ($item['type'] == 'person') {
                    $res[] = [
                        'value' => $item['personFullName'],
                        'desc' => '<i class="far fa-user"></i> ' . $item['personPost'],
                        'category' => $item['parentOrgName'],
                        'unid' => $item['unid'],
                        'unidOrg' => $item['personOrgUnid'],
                        'tel1' => $item['personTel1'],
                        'tel2' => $item['personTel2'],
                        'type' => 'person',                        
                        'img' => $item['photo'],
                    ];
                }
                if ($item['type'] == 'org') {
                    $res[] = [
                        'value' => $item['orgName'],
                        'desc' => '<i class="far fa-building"></i> ' . $item['orgCode'],
                        'category' => '',
                        'unid' => $item['unid'],
                        'unidOrg' => $item['unid'],
                        'type' => 'org',
                    ];
                }
            }
            return $res;
        }
        return [];
    }

    /**
     * Поиск по ФИО и телефону
     * @param string $term
     * @return string
     */
    public function actionSearch($term)
    {                
        $telephoneSearch = new TelephoneSearch();
        return $this->renderAjax('search', [
            'result' => $telephoneSearch->searchTerm($term),
        ]);
    }     


}
