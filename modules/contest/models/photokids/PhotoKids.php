<?php

namespace app\modules\contest\models\photokids;

use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\FileHelper;

class PhotoKids
{

    const PATH = '/public/upload/contest/photo-kids/{id}/'; 

    /**
     * Текущие задания
     * @return array
     */
    public static function getToday()
    {
        return (new Query())
            ->select('t.*')
            ->from('{{%contest_photo_kids}} t')
            ->leftJoin('{{%contest_photo_kids_answer}} answ', 't.id = answ.id_photo_kids and answ.username = :username', [
                ':username' => Yii::$app->user->identity->username
            ])
            ->where([
                'answ.id' => null,                
            ])
            ->andWhere(['<=', 't.datetime_start', new Expression('getdate()')])
            ->andWhere(['>=', 't.datetime_end', new Expression('getdate()')])
            ->all();            
    }


    /**
     * Поиск изображений
     * @return array|null
     */
    public static function getImages($id)
    {
        $path = str_replace('{id}', $id, self::PATH);
        $fullPath = Yii::getAlias('@webroot' . $path);       
        if (is_dir($fullPath)) {
            $res = FileHelper::findFiles($fullPath, [
                'only' => ['*.jpg', '*.jpeg', '*.png', '*.gif', '*.bmp'],                
                'caseSensitive' => false,
            ]);
            return array_map(function($fileName) use ($path) {
                return $path . basename($fileName);
            }, $res);
        }
        return null;
    }


    /**
     * Сохранение результата
     * @param int $id
     * @param string $answer
     * @return int
     */
    public static function saveResult($id, $answer)
    {
        if (!self::checkRights($id)) {
            return 0;
        }
        return Yii::$app->db->createCommand()
            ->insert('{{%contest_photo_kids_answer}}', [
                'id_photo_kids' => $id,
                'fio' => $answer,
                'username' => Yii::$app->user->identity->username,
                'date_create' => new Expression('getdate()'),
            ])
            ->execute();
    }


    /**
     * Проверка возможности ответа 
     * @return bool
     */
    public static function checkRights($id)
    {
        // могут отвечать не гости
        if (Yii::$app->user->isGuest) {
            return false;
        }
        // только сотрудники УФНС
        if (!Yii::$app->user->identity->isOrg('8600')) {
            return false;
        }
        // и если еще не отвечал
        return !(new Query())
            ->from('{{%contest_photo_kids_answer}}')
            ->where([
                'username' => Yii::$app->user->identity->username,
                'id_photo_kids' => $id,
            ])
            ->exists();
    }

    /**
     * Показать результаты (по предыдущим вопросам)
     * @return array
     */
    public static function getResults()
    {
        return (new Query())
            ->from('{{%contest_photo_kids}}')            
            ->where(['<=', 'datetime_end', new Expression('getdate()')])
            ->orderBy(['datetime_start' => SORT_DESC])
            ->all();             
    }
   
}
