<?php
namespace app\modules\admin\modules\grantaccess\controllers;

use app\components\Controller;
use app\modules\admin\modules\grantaccess\models\GrantAccessGroup;
use app\modules\admin\modules\grantaccess\models\GrantAccessGroupAdGroup;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\NotFoundHttpException;

class AdGroupController extends Controller
{
    /**
     * {@inheritDoc}
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
                        'roles' => ['admin'],
                    ],                    
                ],
            ],
        ];
    }

    /**
     * @param string $unique
     */
    public function actionIndex($unique)
    {
        $modelGroup = $this->findModelGroup(['unique' => $unique]);
        if ($modelGroup === null) {
            return '';
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $modelGroup->getAdGroups(),
        ]);
        
        return $this->renderAjax('index', [
            'unique' => $unique,
            'idGroup' => $modelGroup->id,
            'dataProvider' => $dataProvider,            
        ]);
    }

    /**
     * @param int $idGroup
     */
    public function actionCreate($idGroup)
    {
        $this->findModelGroup($idGroup);

        $model = new GrantAccessGroupAdGroup([
            'id_group' => $idGroup,
        ]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }

        return $this->render('form', [
            'model' => $model,
        ]);
    }

    /**
     * @param int $id
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        return $model->delete();
    }

    /**
     * @return GrantAccessGroup|null
     * @throws NotFoundHttpException
     */
    private function findModelGroup($id)
    {
        if ($model = GrantAccessGroup::findOne($id)) {
            return $model;
        }
        throw new NotFoundHttpException();
    }

    /**
     * @return GrantAccessGroupAdGroup|null
     * @throws NotFoundHttpException
     */
    private function findModel($id)
    {
        if ($model = GrantAccessGroupAdGroup::findOne($id)) {
            return $model;
        }
        throw new NotFoundHttpException();
    }

}
