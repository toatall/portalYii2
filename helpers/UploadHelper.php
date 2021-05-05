<?php
namespace app\helpers;

/**
 * Class UploadHelper
 * @package app\helpers
 */
class UploadHelper
{
    /**
     * @var string
     */
    private $path;

    /**
     * UploadHelper constructor.
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * Загрузка файлов на сервер
     * @param $uploadFiles
     * @param null $funcAfterUploadSuccess
     */
    public function uploadFiles($uploadFiles, $funcAfterUploadSuccess = null)
    {
        if (!is_array($uploadFiles)) {
            $uploadFiles[] = $uploadFiles;
        }

        $path = $this->path;

        foreach ($uploadFiles as $file) {
            if (!$file instanceof \yii\web\UploadedFile) {
                continue;
            }

            $saveFile = \Yii::$app->storage->saveUploadedFile($file, $path);

            if (empty($saveFile)) {
                \Yii::error("Не удалось сохранить файл {$file->name} в {$path}");
            }
            else
            {
                if (is_callable($funcAfterUploadSuccess)) {
                    call_user_func($funcAfterUploadSuccess, $file, $saveFile, $path);
                }
            }
        }
    }



}