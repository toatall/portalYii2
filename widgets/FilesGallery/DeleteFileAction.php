<?php
namespace app\widgets\FilesGallery;

use Yii;
use yii\base\Action;
use yii\helpers\FileHelper;
use yii\web\ServerErrorHttpException;

class DeleteFileAction extends Action
{

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        $request = Yii::$app->request;
        $files = $request->post('files');
        if (empty($files)) {
            throw new ServerErrorHttpException('Не передан массив files!');
        }
        if (!is_array($files)) {
            throw new ServerErrorHttpException('Поле files не является массивом!');
        }
        $root = Yii::getAlias('@webroot');
        foreach ($files as $file) {
            FileHelper::unlink($root . $file);
        }
    }

}