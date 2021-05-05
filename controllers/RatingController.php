<?php

namespace app\controllers;

use yii\web\NotFoundHttpException;
use app\models\Tree;
use app\models\rating\RatingMain;

class RatingController extends \yii\web\Controller
{

    /**
     * Рейтинг
     * @param $idTree
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionIndex($idTree)
    {
        if (\Yii::$app->request->isAjax) {
            return $this->renderAjax('index', [
                'modelTree' => $this->findTree($idTree),
                'modelsRatingMain' => $this->findRatingsMain($idTree),
            ]);
        }

        return $this->render('index', [
            'modelTree' => $this->findTree($idTree),
            'modelsRatingMain' => $this->findRatingsMain($idTree),
        ]);
    }

    /**
     * @param $id
     * @return Tree
     * @throws NotFoundHttpException
     */
    private function findTree($id)
    {
        if (($model = Tree::findPublic()->andWhere(['id'=>$id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
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

    public function tree($idTree)
    {
        return $this->actionIndex($idTree);
    }

}
