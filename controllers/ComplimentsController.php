<?php

namespace app\controllers;

use app\models\christmascalendar\ChristmasCalendar;
use app\models\christmascalendar\ChristmasCalendarQuestion;
use app\models\ComplimentsLike;
use app\models\news\NewsSearch;
use app\models\page\PageSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\components\Controller;

/**
 * @package app\controllers
 */
class ComplimentsController extends Controller
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
     * Главная страница
     * Показать все поздравления
     * @return string
     * @throws \Exception
     */
    public function actionIndex()
    {
        $files = [];
        foreach ($this->findFiles() as $file) {
            $fileInfo = pathinfo($file);
            $files[] = [
                'file' => $this->getPath() . '/' . basename($file),
                'title' => isset($fileInfo['filename']) ? $fileInfo['filename'] : basename($file),
                'count_like' => (new Query())->from('{{%compliments_like}}')->where(['file_name'=>basename($file)])->count(),
                'is_liked' => (new Query())->from('{{%compliments_like}}')
                    ->where(['file_name'=>basename($file), 'username'=>\Yii::$app->user->identity->username])
                    ->exists(),
            ];
        }
        array_multisort(array_column($files, 'count_like'), SORT_DESC, $files);
        return $this->render('index', [
            'files' => $files,
        ]);
    }

    /**
     * @param $filename
     * @return int|string
     */
    public function actionLike($filename)
    {
        $model = new ComplimentsLike();
        $model->file_name = $filename;
        $model->save();

        return (new Query())
            ->from('{{%compliments_like}}')
            ->where(['file_name' => $filename])
            ->count();
    }

    /**
     * @return array
     */
    private function findFiles()
    {
        $root = \Yii::getAlias('@webroot') . $this->getPath();
        $files = FileHelper::findFiles($root, ['filter' => function($path) {
            $file = pathinfo($path);
            return (isset($file['extension']) && ($file['extension'] == 'mp4'));
        }]);
        return $files;
    }

    /**
     * @return string
     */
    private function getPath()
    {
        return '/files_static/pozdravleniya';
    }



}
