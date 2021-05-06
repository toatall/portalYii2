<?php

namespace app\controllers;

use app\models\news\NewsSearch;
use app\models\page\PageSearch;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;

/**
 * Class VovController
 * @package app\controllers
 */
class VovController extends \yii\web\Controller
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
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Летопись войны
     * @return string
     */
    public function actionNews()
    {
        $searchModel = new NewsSearch();
        $searchModel->tags = '75';
        $dataProvider = $searchModel->searchPublic(\Yii::$app->request->queryParams);
        return $this->renderAjax('news', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Война в лицах
     * @return string
     */
    public function actionFaceNews()
    {
        $searchModel = new PageSearch();
        $searchModel->tags = '75face';
        $dataProvider = $searchModel->searchPublic(\Yii::$app->request->queryParams);
        return $this->renderAjax('news', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Карусель дедов
     * @return string
     */
    public function actionFaceCarousel()
    {
        $dirVov = '/repository/vov/';
        $path = \Yii::getAlias('@webroot') . $dirVov;
        $files = [];
        if (file_exists($path) && is_dir($path)) {
            $files = FileHelper::findFiles($path, ['except' => ['*.db']]);
        }       
        $items = array_map(function ($item) use ($dirVov) {
            //$file = basename(iconv('windows-1251', 'utf-8', $item));    
            $file = basename($item);
            return [
                'content' => '<img src="' . $dirVov . $file . '" style="max-width: 900px;" />',

            ];
        }, $files);

        // загрузка фоточек
        return $this->renderPartial('carousel', [
            'files' => $items,
        ]);
    }

    /**
     * Тестирование
     * @return string
     */
    public function actionTest()
    {
        return $this->renderAjax('test');
    }

    /**
     * Живые строки войны
     * @return string
     * @throws \Exception
     */
    public function actionLiveRowsWar()
    {
        $searchModel = new NewsSearch();
        $searchModel->id = 8309;
        $dataProvider = $searchModel->searchPublic(\Yii::$app->request->queryParams);
        return $this->renderAjax('liveRowsWar', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'videos' => $this->dataLiveRowsWar(),
        ]);
    }

    /**
     * @return array
     * @throws \Exception
     */
    protected function dataLiveRowsWar()
    {
        $root = \Yii::getAlias('@webroot');
        $fileConfig = $root . "/files_static/liveRowsWar/config.json";
        if (!file_exists($fileConfig)) {
            throw new \Exception("Файл {$fileConfig} не найден!");
        }

        $result = [];
        $fileConfigData = file_get_contents($fileConfig);
        $fileConfigData = json_decode($fileConfigData, true);
        foreach ($fileConfigData['data'] as $item) {
            $result[$item['name']] = $this->findFiles($item['path']);
        }
        return $result;
    }

    /**
     * @param $path
     * @return array
     */
    private function findFiles($path)
    {
        $root = \Yii::getAlias('@webroot');
        $files = FileHelper::findFiles(/*iconv('utf-8', 'windows-1251', $root . $path)*/ $root . $path, ['except' => ['*.db']]);
        $items = array_map(function ($item) use ($path) {
            return $path . basename(/*iconv('windows-1251', 'utf-8', */$item/*)*/);
        }, $files);
        return $items;
    }

    /**
     * @return string
     */
    public function actionAlley($id=0)
    {
        if ($id > 0) {
            $result = $this->loadAlley($id);
        }
        else {
            $result = $this->loadAlley();
        }
        return $this->render('alley', [
            'result' => $result,
            'list' => ($id == 0),
        ]);
    }

    /**
     * @param int $id
     * @return mixed
     */
    private function loadAlley($id=0)
    {
        $query = new Query();
        $db = \Yii::$app->dbPortalOld;
        $query->from('vov')->orderBy(['fio' => SORT_ASC]);
        if ($id>0) {
            $query->where(['id' => $id]);
            return $query->one($db);
        }
        return $query->all($db);
    }

}
