<?php
namespace app\modules\contest\models;

use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\web\HttpException;

class Map 
{


    public static function listCities()
    {        
        $file = Yii::getAlias('@webroot') . '/public/content/map/cities.txt';
        if (!file_exists($file)) {
            throw new HttpException(500, "Файл $file не найден!");
        }
        $res = explode("\n", file_get_contents($file));
        return array_combine($res, $res);
    }

    public static function listRegions()
    {
        $file = Yii::getAlias('@webroot') . '/public/content/map/map_regions_russia.json';
        if (!file_exists($file)) {
            throw new HttpException(500, "Файл $file не найден!");
        }
        return json_decode(file_get_contents($file), true);
    }

    public static function findAll()
    {
        return (new Query())
            ->from('{{%contest_map}}')
            ->all();
    }

    public static function findToday()
    {
        return (new Query())
            ->from('{{%contest_map}}')
            ->where(['date_show' => new Expression('cast(getdate() as date)')])
            ->orderBy(['date_show' => SORT_ASC])
            ->one();
    }

    /**
     * Отвечал ли текущий пользователь
     * @return array|null
     */
    public static function isAnswered($id)
    {
        if ($id == null) {
            return null;
        }

        return (new Query())
            ->from('{{%contest_map_answer}}')
            ->where([
                'username' => Yii::$app->user->identity->username,
                'id_contest_map' => $id,
            ])
            ->one();
    }

    public static function saveAnswer($city, $id)
    {        
        if (!self::isAnswered($id)) {
            Yii::$app->db->createCommand()
                ->insert('{{%contest_map_answer}}', [
                    'id_contest_map' => $id,
                    'place_name' => $city,
                    'username' => Yii::$app->user->identity->username,
                    'date_create' => new Expression('getdate()'),                    
                ])
                ->execute();
        }
    }

}