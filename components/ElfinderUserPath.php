<?php
namespace app\components;

use Yii;
use mihaildev\elfinder\volume\Local;


class ElfinderUserPath extends Local
{
    public function isAvailable()
    {
        if(Yii::$app->user->isGuest) {
            return false;
        }
        return parent::isAvailable();
    }

    public function getUrl()
    {
        $path = strtr($this->path, ['{username}'=>Yii::$app->user->identity->username]);
        $path = strtr($path, ['{id}'=>Yii::$app->user->id]);
        return Yii::getAlias($this->baseUrl.'/'.trim($path,'/'));
    }

    public function getRealPath()
    {
        $path = strtr($this->path, ['{username}'=>Yii::$app->user->identity->username]);
        $path = strtr($path, ['{id}'=>Yii::$app->user->id]);
        $path = Yii::getAlias($this->basePath.'/'.trim($path,'/'));
        if(!is_dir($path))
        {
            mkdir($path, 0777, true);
        }
        return $path;
    }
}