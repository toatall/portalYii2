<?php
namespace app\models;

use Yii;
use yii\db\Expression;
use yii\db\Query;

class History
{
    /**
     * @return \yii\db\Connection
     */
    protected static function getDb()
    {
        return Yii::$app->db;
    }

    /**
     * @return \yii\web\Request
     */
    protected static function getRequest()
    {
        return Yii::$app->request;
    }

    /**
     * @param string $url
     * @param string $title
     */
    public static function save($url, $title)
    {
        $record = (new Query())
            ->from('{{%history}} WITH(NOLOCK)')
            ->where([
                'url' => $url, 
            ])
            ->andWhere('[[date]] = CAST(GETDATE() AS DATE)')
            ->one();

        if ($record == null) {
            self::getDb()->createCommand()
                ->insert('{{%history}}', [
                    'url' => $url,
                    'count_visits' => 1,
                    'date' => new Expression('CAST(GETDATE() AS DATE)'),
                    'title' => $title,
                ])
                ->execute();
            $id = Yii::$app->db->getLastInsertID();
        }
        else {
            $id = $record['id'];
        }

        self::saveVisit($id);
    }

    /**
     * @param int $idParent
     */
    protected static function saveVisit($idParent)
    {
        $request = self::getRequest();
           
        Yii::$app->db->createCommand()
            ->insert('{{%history_detail}}', [
                'id_history' => $idParent,
                'is_ajax' => $request->isAjax,
                'is_pjax' => $request->isPjax,
                'method' => $request->method, 
                'host' => $request->userHost,
                'ip' => $request->userIP,
                'date_create' => time(),                
                'author' => Yii::$app->user->identity->username ?? 'guest',
                'author_org_code' => Yii::$app->user->identity->default_organization ?? null,
            ])->execute();
        
        self::getDb()->createCommand()
            ->update('{{%history}}', [
                'count_visits' => new Expression('(SELECT COUNT(*) FROM {{%history_detail}} WHERE id_history = :id)', [':id' => $idParent])
            ], ['id' => $idParent])
            ->execute();
            
    }

    /**
     * Количество просмотров
     * @return int
     */
    public static function count($url)
    {
        return (new Query())
            ->from('{{%history}} WITH(NOLOCK)')
            ->where(['url' => $url])
            ->sum('[[count_visits]]');
    }
}