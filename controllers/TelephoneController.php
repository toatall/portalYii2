<?php

namespace app\controllers;

use app\models\telephone\TelephoneSearch;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\db\Query;

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
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex($organizationUnid=null)
    {    
        $telephoneSearch = new TelephoneSearch();

        return $this->render('index', [
            'organizations' => $this->getOrganizations(),
            'organizationDataProvider' => $telephoneSearch->search($organizationUnid),
            'organizationUnid' => $organizationUnid,
        ]);
    }

    /**
     * @return array
     */
    public function getOrganizations()
    {
        return (new Query())
            ->from('{{%telephone_department}}')
            ->where([
                'unid_parent' => null,
                'form' => 'Organization',
            ])
            ->orderBy(['org_code' => SORT_ASC])
            ->all();
    }

     


}
