<?php
namespace app\modules\like\controllers;

use app\components\Controller;
use app\modules\like\models\Like;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;

class LikeController extends Controller 
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
     * @param int $idLike
     * @return mixed
     */
    public function actionIndex($idLike)
    {
        $model = $this->findModel($idLike);
        
        $isViewLikers = $model->isViewLikers();
        if ($isViewLikers) {
            $detailDataProvider = new ActiveDataProvider([
                'query' => $model->getLikeDatas()->with('usernameModel'),
                'sort' => [
                    'defaultOrder' => ['date_create' => SORT_DESC],
                ],
            ]);
        }
        
        return $this->render('index', [
            'isViewLikers' => $isViewLikers,
            'detailDataProvider' => $detailDataProvider ?? null,
            'groupDataByOrg' => $model->getLikeDatasGroupByOrganization(),
            'groupDataByDate' => $model->getLikeByDate(),
            'idLike' => $idLike,
        ]);
    }

    /**
     * @return Like|null
     */
    private function findModel($id)
    {
        if (($model = Like::findOne($id)) === null) {
            throw new NotFoundHttpException();
        }
        return $model;
    }
    

}