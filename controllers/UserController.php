<?php

namespace app\controllers;

use app\components\Ldap;
use app\components\LdapResult;
use app\helpers\UploadHelper;
use app\models\User;
use Exception;
use Yii;
use yii\db\Expression;
use yii\filters\AccessControl;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

class UserController extends \yii\web\Controller
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
     * Профиль пользователя
     * @return string
     */
    public function actionProfile($updateInformation=false, $deletePhoto=false)
    {        
        if ($updateInformation) {
            if (!$this->updateUserADInformation()) {
                Yii::$app->session->setFlash('danger', 'Произошла ошибка при обновлении информации!');                
            }
            else {
                Yii::$app->session->setFlash('success', 'Информация обновлена!');
            }
        }

        if ($deletePhoto && Yii::$app->request->isPost) {
            if (!$this->deleteUploadedPhoto()) {
                Yii::$app->session->setFlash('danger', 'Произошла ошибка при удалении фотографии!');
            }
            else {
                Yii::$app->session->setFlash('success', 'Фотография успешно удалена!');
            }
        }

        $photoFile = UploadedFile::getInstanceByName('photo');
        if ($photoFile !== null) {            
            if (!$this->uploadPhotoFile($photoFile)) {
                Yii::$app->session->setFlash('danger', 'При загрузке фотографии произошла ошибка, попробуйте снова!');
            }
            else {
                Yii::$app->session->setFlash('success', 'Фотография успешно загружена!');
            }
        }

        $model = $this->findCurrentUserModel();

        return $this->render('profile', [
            'model' => $model,
        ]);
    }


    /**
     * Поиск User текущего пользователя
     * @return User
     */
    private function findCurrentUserModel()
    {
        $username = Yii::$app->user->identity->username;
        if (($model = User::find()->where(['username' => $username])->one()) === null) {
            throw new NotFoundHttpException('Page not found!');
        }
        return $model;
    }    

    /**
     * Процедура загрузка фотографии
     * @param UploadedFile $file
     * @return boolean
     */
    private function uploadPhotoFile(UploadedFile $file)
    {
        $model = $this->findCurrentUserModel();
        $basePath = $this->getWebPath();

        // если файл уже загружен ранее, то удаляем его
        if ($model->photo_file != null) {
            if (file_exists($basePath . $model->photo_file)) { 
                FileHelper::unlink($basePath . $model->photo_file);              
            }
        }
        
        $fileInfo = pathinfo($file->name);
        $fullFileName = $this->getUploadPath() . Yii::$app->user->identity->username . '.' . $fileInfo['extension'];
        FileHelper::createDirectory($basePath . $this->getUploadPath());
        if ($file->saveAs($basePath . $fullFileName)) {
            $model->photo_file = $fullFileName;
            return $model->save(false, ['photo_file']);
        }
        return false;
    }

    /**
     * Удаление фотографии (файла и записи)
     * @return boolean
     */
    private function deleteUploadedPhoto()
    {
        $model = $this->findCurrentUserModel();
        $basePath = $this->getWebPath();

        if (file_exists($basePath . $model->photo_file)) { 
            FileHelper::unlink($basePath . $model->photo_file);
        }
        $model->photo_file = null;
        return $model->save();
    }

    
    /**
     * @return string базовый каталог
     */
    private function getWebPath()
    {
        return Yii::$app->basePath . DIRECTORY_SEPARATOR . 'web';
    }

    /**
     * @return string каталог для загрузки фотографий пользователей
     */
    private function getUploadPath()
    {
        return Yii::$app->params['user']['profile']['upload'];
    }

    /**
     * Обновление информации из Active Directory
     */
    private function updateUserADInformation()
    {
        $model = $this->findCurrentUserModel();        
        try {
            /** @var Ldap $ldap */
            $ldap = Yii::$app->ldap;
            $user = $ldap->filter("(sAMAccountName={$model->username})")->one();
            if ($user) {
                $model->fio = $user->asText('cn') ?? $model->fio;
                $model->telephone = $user->asText('telephonenumber') ?? $model->telephone;
                $model->post = $user->asText('title') ?? $model->post;
                $model->department = $user->asText('department') ?? $model->department;
                $model->organization_name = $user->asText('company') ?? $model->organization_name;
                $model->mail_ad = $user->asText('mail') ?? $model->mail_ad;
                $model->room_name_ad = $user->asText('physicalDeliveryOfficeName') ?? $model->room_name_ad;
                $model->description_ad = $user->asText('description') ?? $model->description_ad;
                $model->memberof = implode(', ', $user->asArray('memberOf') ?? []) ?? $model->memberof;
                $model->date_update_ad = new Expression('getdate()');
                return $model->save();
            }
        }
        catch (Exception $ex) {
            Yii::$app->session->setFlash('warning', $ex->getMessage());
        }
        return false;
    }

    
}
