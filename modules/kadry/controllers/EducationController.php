<?php

namespace app\modules\kadry\controllers;

use app\modules\kadry\models\education\Education;
use app\modules\kadry\models\education\EducationDataFiles;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * Education controller for the `kadry` module
 */
class EducationController extends Controller
{
    // шаблон
    public $layout = 'education';

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
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [            
            'models' => Education::find()->all(),
        ]);
    }

    /**
     * Просмотр информации о курсе
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->findEducationById($id);
        $model->saveVisit();
        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Скачивание (просмотр) файла
     * @return string
     */
    public function actionDownload($id)
    {
        $model = $this->findEducationDataFile($id);
        $model->saveDownloadVisit();
        $file = Yii::getAlias('@webroot') . $model->filename;
        $mime= FileHelper::getMimeType($file);
        ob_clean();
        header("Content-Type: $mime;", true);
        header("Content-Length: " . filesize($file));
        header("Content-Disposition: attachment; filename=" . basename($file));
        readfile($file);        
    }

    /**
     * Поиск курса по идентификатору
     * @return Education
     */
    protected function findEducationById($id)
    {
        if (($model = Education::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }    

    /**
     * @return EducationDataFiles
     */
    protected function findEducationDataFile($id)
    {
        if (($model = EducationDataFiles::findOne($id)) === null) {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
        return $model;
    }
        
}
