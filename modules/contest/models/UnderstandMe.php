<?php
namespace app\modules\contest\models;

use Yii;
use yii\db\Expression;
use yii\db\Query;

class UnderstandMe
{    

    const PATH = '/public/upload/contest/understand_me/';
    const JSON_FILE = 'data.json';

    public static function getData()
    {
        $file = Yii::getAlias('@webroot') . self::PATH . self::JSON_FILE;
        return json_decode(file_get_contents($file));
    }

    public static function getItemById($id)
    {
        $data = self::getData();
        foreach($data->data as $d) {
            if ($d->id == $id) {
                return $d;
            }
        }
        return null;
    }

} 