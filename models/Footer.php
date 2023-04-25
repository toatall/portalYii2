<?php
namespace app\models;

use Yii;
use yii\db\Query;

/**
 * Формирование футера для шаблона портала
 * @author toatall
 */
class Footer
{
    /**
     * Название ключа кэша для хранения футера
     * @return string
     */
    public static function getCahceName()
    {
        return 'portal.footer';
    }

    /**
     * Формирование ссылок в футер
     * @return array
     */
    public static function getLinks()
    {       
        $query = (new Query())
            ->from('{{%footer_data}} footer_data')
            ->leftJoin('{{%footer_type}} footer_type', 'footer_type.id = footer_data.id_type')
            ->select('footer_data.*, footer_type.name')            
            ->all();

        $result = [];
        foreach($query as $row) {
            $result[$row['name']][] = $row;
        }
        return $result;
    }

    /**
     * Очистка кэша
     */
    public static function clearCache()
    {
        Yii::$app->cache->delete(self::getCahceName());
    }

}