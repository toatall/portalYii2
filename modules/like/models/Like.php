<?php

namespace app\modules\like\models;

use Exception;
use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "{{%like}}".
 *
 * @property int $id
 * @property string $unique
 * @property int|null $count
 * @property string|null $filter_allow
 *
 * @property LikeData[] $likeDatas
 * @property LikeData $likeDataCurrentUser
 */
class Like extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%like}}';
    }    

    /**
     * Gets query for [[LikeDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getLikeDatas()
    {
        return $this->hasMany(LikeData::class, ['id_like' => 'id']);
    }


    /**
     * Поиск записи с $unique
     * Если запись не нейдена, то создается новавя запись
     * 
     * @param string $unique
     * @return Like
     */
    public static function findDefinitely($unique, $filterAllow)
    {
        $filterStr = implode(',', (array) $filterAllow);
        /** @var Like $model */
        $model = Like::findOne(['unique' => $unique]);
        if ($model === null) {
            $model = new Like([
                'unique' => $unique,
                'count' => 0,
                'filter_allow' => $filterStr,
            ]);
            if (!$model->save()) {
                throw new Exception('Ошибка сохранения в таблицу {{%like}}. <br />'. print_r($model->getErrors(), true));
            }
        }
        else {
            if ($filterStr != $model->filter_allow) {
                $model->filter_allow = $filterStr;
                $model->save(false, ['filter_allow']);
            }
        }
        return $model;
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLikeDataCurrentUser()
    {
        return $this->hasOne(LikeData::class, ['id_like' => 'id'])
            ->andWhere(['username' => Yii::$app->user->identity->username]);
    }

    /**
     * @param LikeData|null $modelLike
     */
    public function likeToggle($modelLike)
    {
        if ($modelLike === null) {
            (new LikeData([
                'id_like' => $this->id,            
            ]))->save();
        }
        else {
            $modelLike->delete();
        }
        // обновление количества лайков
        Yii::$app->db->createCommand()
            ->update('{{%like}}', [
                'count' => new Expression('(SELECT COUNT(*) FROM {{%like_data}} WHERE id_like = :id)', [':id' => $this->id])
            ], [
                'id' => $this->id,
            ])
            ->execute();           
    }

    /**
     * Проверка прав для просмотра лайкеров
     * 
     * @return bool
     */
    public function isViewLikers()
    {
        $roles = explode(',', $this->filter_allow);
        if (empty($roles) || !is_array($roles)) {
            return false;
        }
        foreach($roles as $role) {
            if ($role == '*') { 
                return true;
            }
            if (Yii::$app->user->can($role)) {
                return true;
            }
        }
        return Yii::$app->user->can('admin');
    }

    /**
     * @return array
     */
    public function getLikeDatasGroupByOrganization()
    {
        return (new Query())
            ->select('[user].default_organization AS [org], COUNT(like_data.id) AS count_likes')
            ->from('{{%like_data}} like_data')
            ->innerJoin('{{%user}} [user]', '[user].username = like_data.username')
            ->groupBy('[user].default_organization')
            ->orderBy(['[user].default_organization' => SORT_ASC])
            ->where(['id_like' => $this->id])
            ->all();
    }

    /**
     * @return array
     */
    public function getLikeByDate()
    {
        return (new Query())
            ->from('{{%like_data}}')
            ->select(new Expression("CAST(DATEADD(s, [date_create], '1970-01-01') AS DATE) as [date], COUNT(id) AS [count_likes]"))
            ->groupBy(new Expression("CAST(DATEADD(s, [date_create], '1970-01-01') AS DATE)"))
            ->where(['id_like' => $this->id])
            ->all();
    }

    /**
     * @return int
     */
    public static function count($unique)
    {
        return (new Query())
            ->from(self::tableName())
            ->select('[[count]]')
            ->where(['unique' => $unique])
            ->one()['count'] ?? 0;
    }

}
