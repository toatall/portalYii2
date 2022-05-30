<?php


namespace app\modules\contest\models\quest;

use Yii;
use yii\db\Expression;
use yii\db\Query;

class Quest 
{
    public static function findResult($step)
    {
        return (new Query())
            ->from('{{%contest_quest}}')
            ->where([
                'step' => $step,
                'username' => Yii::$app->user->identity->username,
            ])
            ->one();
    }
    
    public static function saveResult($step, $balls, $data)
    {
        if (Quest::findResult($step) == null) {
            return Yii::$app->db->createCommand()
                ->insert('{{%contest_quest}}', [
                    'step' => $step,
                    'balls' => $balls,
                    'data' => serialize($data),
                    'username' => Yii::$app->user->identity->username,
                    'date_create' => new Expression('getdate()'),    
                ])
                ->execute();
        }
        return false;
    }


}