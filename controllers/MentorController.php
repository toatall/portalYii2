<?php

namespace app\controllers;

use app\models\mentor\MentorPost;
use app\models\mentor\MentorWays;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use Yii;
use yii\web\UploadedFile;

class MentorController extends \yii\web\Controller
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
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * @return string
     */
    public function actionNormative()
    {
        $models = MentorWays::find()->orderBy(['name' => SORT_ASC])->all();
        return $this->render('normative', [
            'models' => $models,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionWay($id)
    {
        $modelWay = $this->findModelWay($id);
        $dataProvider = new ActiveDataProvider([
            'query' => MentorPost::find()->where(['id_mentor_ways' => $id])->orderBy(['date_create' => SORT_DESC]),
        ]);

        return $this->render('way', [
            'dataProvider' => $dataProvider,
            'modelWay' => $modelWay,
        ]);
    }

    /**
     * @param $id
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->findModelPost($id);

        if (\Yii::$app->request->isAjax) {
            $resultJson = [
                'title'=>$model->title,
                'content' => $this->renderAjax('view', ['model'=>$model]),
            ];
            return Json::encode($resultJson);
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Добавление поста
     * @param $way
     * @return string
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionCreatePost($way)
    {
        $this->checkRight();
        $modelWay = $this->findModelWay($way);
        // ...
        $model = new MentorPost();
        $model->id_mentor_ways = $way;
        $model->id_organization = '8600';
        if ($model->load(Yii::$app->request->post())) {
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            if ($model->save()) {
                return $this->redirect(['/mentor/way', 'id' => $way]);
            }
        }

        return $this->render('post/create', [
            'modelWay' => $modelWay,
            'model' => $model,
        ]);
    }

    /**
     * Изменение поста
     * @param $id
     * @return string|\yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     */
    public function actionUpdatePost($id)
    {
        $this->checkRight();
        $model = $this->findModelPost($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->uploadFiles = UploadedFile::getInstances($model, 'uploadFiles');
            if ($model->save()) {
                return $this->redirect(['/mentor/way', 'id' => $model->id_mentor_ways]);
            }
        }

        return $this->render('post/update', [
            'model' => $model,
        ]);
    }

    /**
     * Удаление поста
     * @param $id
     * @return \yii\web\Response
     * @throws ForbiddenHttpException
     * @throws NotFoundHttpException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeletePost($id)
    {
        $this->checkRight();
        $model = $this->findModelPost($id);
        $model->delete();
        return $this->redirect(['/mentor/way', 'id' => $model->id_mentor_ways]);
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */
    private function findModelWay($id)
    {
        if (($model = MentorWays::find()->where(['id'=>$id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     * @throws NotFoundHttpException
     */
    private function findModelPost($id)
    {
        if (($model = MentorPost::find()->where(['id'=>$id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * @throws ForbiddenHttpException
     */
    private function checkRight()
    {
        if (!MentorPost::isModerator()) {
            throw new ForbiddenHttpException('А вам нельзя!');
        }
    }

}
