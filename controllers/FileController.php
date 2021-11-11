<?php

namespace app\controllers;

use app\models\File;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class FileController extends \yii\web\Controller
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
     * Скачивание файла и фиксирование
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionDownload($id)
    {
        $modelFile = $this->find($id);
        
        if (!file_exists(Yii::getAlias('@webroot') . $modelFile->file_name)) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }

        // запись лога
        $this->writeLog($id);
        
        $pathInfo = pathinfo(\Yii::getAlias('@web') . $modelFile->file_name);
        $fileUrl = $pathInfo['dirname'] . '/' . rawurlencode($pathInfo['basename']);
        
        // переадресация        
        return $this->redirect($fileUrl);        
    }

    /**
     * @param $id
     * @return File
     * @throws NotFoundHttpException
     */
    private function find($id)
    {
        if (($model = File::find()->andWhere(['id'=>$id])->one()) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    /**
     * Запись лога
     * @param $id
     */
    private function writeLog($id)
    {
        $command = \Yii::$app->db->createCommand();
        $command->insert('{{%file_download}}', [
            'id_file' => $id,
            'username' => \Yii::$app->user->isGuest ? 'guest' : \Yii::$app->user->identity->username,
            'session_id' => session_id(),
        ]);
    }

}
