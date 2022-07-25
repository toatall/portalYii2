<?php

namespace app\controllers;

use app\models\page\PageSearch;
use app\models\rating\RatingMain;
use Yii;
use app\models\Tree;
use yii\base\Exception;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\filters\AccessControl;
use app\components\Controller;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TreeController implements the CRUD actions for Tree model.
 */
class TreeController extends Controller
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
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Tree::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Tree model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     * @throws Exception
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $module = $model->module;
        $this->view->title = $model->name;
        return $this->renderModule($module, $model);
    }

    /**
     * @param $id
     * @return string
     * @throws HttpException
     * @throws NotFoundHttpException
     */
    public function actionStatic($id)
    {
        $model = $this->findModel($id);
        if ($model['module'] != 'static') {
            throw new HttpException(500, 'Для данного действия доступны только разделы с модулем static');
        }
        if ($model['view_static'] == '') {
            throw new HttpException(500, 'Не указан путь к представлению');
        }
        return $this->render($model['view_static']);
    }


    /**
     * Finds the Tree model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Tree
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Tree::findPublic()->andWhere(['id'=>$id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $module
     * @param $modelTree
     * @return string
     * @throws Exception
     */
    protected function renderModule($module, $modelTree)
    {
        switch ($module) {
            case 'page': return $this->renderPage($modelTree); break;
            case 'rating': return $this->renderRating($modelTree); break;
            default: throw new Exception(500, 'Отсутствует модуль "' . $module . '"');
        }
    }


    /**
     * @param $modelTree
     * @return string
     */
    protected function renderPage($modelTree)
    {
        $searchModel = new PageSearch();
        $dataProvider = new ActiveDataProvider([
            'query' => $searchModel::find()
                ->where([
                    'date_delete' => null,
                    'id_tree' => $modelTree['id'],
                ]),
        ]);

        if ($dataProvider->count == 1) {
            $currentModel = $dataProvider->getModels()[0];
            return $this->render('/news/view', [
                'model' => $currentModel,
            ]);
        }
        else {
           return $this->render('/news/index', [
               'searchModel' => $searchModel,
               'dataProvider' => $dataProvider,
           ]);
        }
    }

    /**
     * @param $modelTree
     * @return string
     */
    protected function renderRating($modelTree)
    {
        return $this->render('/rating/index', [
            'modelTree' => $modelTree,
            'modelsRatingMain' => $this->findRatingsMain($modelTree->id),
        ]);
    }

    /**
     * @param $idTree
     * @return RatingMain[]
     */
    private function findRatingsMain($idTree)
    {
        $models = RatingMain::find()
            ->where([
                'id_tree'=>$idTree,
            ]);
        return $models->all();
    }
}
