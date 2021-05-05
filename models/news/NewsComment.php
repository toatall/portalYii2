<?php

namespace app\models\news;

use Yii;
use app\models\User;

/**
 * This is the model class for table "{{%news_comment}}".
 *
 * @property int $id
 * @property int $id_news
 * @property string $comment
 * @property string $username
 * @property string|null $ip_address
 * @property string|null $hostname
 * @property string $date_create
 * @property string|null $date_delete
 *
 * @property News $news
 * @property User $modelUser
 */
class NewsComment extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news_comment}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['comment', 'username'], 'required'],
            [['id_news'], 'integer'],
            [['comment'], 'string'],
            [['date_create', 'date_delete'], 'safe'],
            [['username', 'hostname'], 'string', 'max' => 250],
            [['ip_address'], 'string', 'max' => 50],
            [['id_news'], 'exist', 'skipOnError' => true, 'targetClass' => News::class, 'targetAttribute' => ['id_news' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_news' => 'Новость',
            'comment' => 'Комментарий',
            'username' => 'Автор',
            'ip_address' => 'IP адрес',
            'hostname' => 'Имя хоста',
            'date_create' => 'Дата создания',
            'date_delete' => 'Дата удаления',
        ];
    }

    /**
     * Актуальные комментарии для новости
     * @param int $idNews
     * @return array
     */
    public static function actualComments($idNews)
    {
        /*
        $query = new \yii\db\Query();
        return $query->from(self::tableName() . ' t')
            ->select('t.id, t.username, t.date_create, t.comment, t_user.fio')
            ->leftJoin('{{%user}} t_user', 't_user.username=t.username')
            ->where(['t.date_delete' => null])
            ->andWhere(['t.id_news' => $idNews])
            ->orderBy(['t.date_create' => SORT_DESC])
            ->all();
        */
        return static::find()
            ->where([
                'date_delete' => null,
                'id_news' => $idNews,
            ])
            ->orderBy(['date_create' => SORT_DESC])
            ->all();
        }

    /**
     * Gets query for [[News]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasOne(News::class, ['id' => 'id_news']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getModelUser()
    {
        return $this->hasOne(User::class, ['username_windows' => 'username']);
    }

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function beforeValidate()
    {
        $this->username = Yii::$app->user->identity->username;
        return parent::beforeValidate();
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->ip_address = $this->getIpAddress();
        $this->hostname = gethostbyaddr($this->ip_address);
        return parent::beforeSave($insert);
    }

    /**
     * Получить ip адрес удаленного хоста
     * @return string
     */
    private function getIpAddress()
    {
        if (YII_ENV_TEST) {
            return '127.0.0.1';
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}
