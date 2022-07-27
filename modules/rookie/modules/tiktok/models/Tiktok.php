<?php

namespace app\modules\rookie\modules\tiktok\models;

use app\behaviors\AuthorBehavior;
use app\models\department\Department;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Query;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "{{%tiktok}}".
 *
 * @property int $id
 * @property int $department_id
 * @property string $description
 * @property string $filename
 * @property int $rate_1
 * @property int $rate_2
 * @property int $rate_3
 * @property int $date_create
 * @property int $date_update
 * @property string $author
 * 
 * @property string $avgRate1
 * @property string $avgRate2
 * @property string $avgRate3
 * @property string $countVotes
 * 
 * @property Department $departmentModel
 *
 * @property TiktokVote[] $tiktokVotes
 */
class Tiktok extends \yii\db\ActiveRecord
{

    private $_avgRate1;
    private $_avgRate2;
    private $_avgRate3;
    private $_countVotes;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tiktok}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['department_id', 'filename'], 'required'],
            [['department_id', 'date_create', 'date_update'], 'integer'],
            [['filename'], 'string', 'max' => 1000],
            [['author'], 'string', 'max' => 250],
            [['description'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'department_id' => 'Department',
            'description' => 'Description',
            'filename' => 'File name',
            'date_create' => 'Date create',
            'date_update' => 'Date update',
            'author' => 'Author',
            'rate_1' => 'Креативность',
            'rate_2' => 'Творчество',
            'rate_3' => 'Качество видеоролика',
        ];
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [            
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],        
        ];
    }

    /**
     * Gets query for [[TiktokVotes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTiktokVotes()
    {
        return $this->hasMany(TiktokVote::class, ['id_tiktok' => 'id'])
            ->andWhere(['author' => \Yii::$app->user->identity->username ?? null]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentModel()
    {
        return $this->hasOne(Department::class, ['id' => 'department_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert)
    {
        if ($insert) {
            $this->rate_1 = 0;
            $this->rate_2 = 0;
            $this->rate_3 = 0;
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        $query = (new Query())
            ->from('{{%tiktok_vote}}')
            ->where(['id_tiktok' => $this->id])
            ->select('avg(cast(rate_1 as float)) avg_rate_1, avg(cast(rate_2 as float)) avg_rate_2, avg(cast(rate_3 as float)) avg_rate_3, count(id) count_votes')
            ->one();        
        $this->_avgRate1 = $query['avg_rate_1'] ?? 0;
        $this->_avgRate2 = $query['avg_rate_2'] ?? 0;
        $this->_avgRate3 = $query['avg_rate_3'] ?? 0;
        $this->_countVotes = $query['count_votes'] ?? 0;
    }

    /**
     * @return string
     */
    private static function getPath()
    {
        return Yii::$app->params['modules']['rookie']['tiktok']['videos'];
    }

    /**
     * @return array|null
     */
    public static function getVideos()
    {
        $url = self::getPath();
        $dir = Yii::getAlias('@webroot') . $url;
        if (is_dir($dir)) {
            $files = FileHelper::findFiles($dir);
            $result = [];
            foreach($files as $file) {
                $result[$url . basename($file)] = basename($file);
            }
            return $result;
        }
        return [];
    }


    /**
     * @return string
     */
    public function getAvgRate1()
    {
        return Yii::$app->formatter->asDecimal($this->_avgRate1, 2);
    }

    /**
     * @return string
     */
    public function getAvgRate2()
    {
        return Yii::$app->formatter->asDecimal($this->_avgRate2, 2);
    }

    /**
     * @return string
     */
    public function getAvgRate3()
    {
        return Yii::$app->formatter->asDecimal($this->_avgRate3, 2);
    }

    /**
     * @return string
     */
    public function getCountVotes()
    {
        return $this->_countVotes;
    }

    /**
     * @return array
     */
    public static function getVideosNotUse()
    {
        $videosFiles = self::getVideos();
        $videosDb = (new Query())
            ->from(self::tableName())
            ->select('filename')
            ->groupBy('filename')
            ->indexBy('filename')
            ->all();
        return array_filter($videosFiles, function($key) use ($videosDb) {
            return !isset($videosDb[$key]);
        }, ARRAY_FILTER_USE_KEY);
    }


    /**
     * @return bool
     */
    public function canVote()
    {
        // проверка возможности голосования
        $modelTikTok = Tiktok::findOne($this->id);
        if ($modelTikTok === null) {
            throw new NotFoundHttpException('Page not found');
        }
        if (!self::isAllowVote($modelTikTok)) {
            // throw new ServerErrorHttpException('Вы не можете голосовать!');
            return false;
        }
        return ($modelTikTok->tiktokVotes == null);
    }

    /**
     * @param \app\modules\rookie\modules\tiktok\models\Tiktok $modelTikTok
     * @return bool
     */
    public static function isAllowVote($modelTikTok)
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        /** @var \app\models\User $identity */        
        $identity = Yii::$app->user->identity;
        if (!$identity->isOrg('8600')) {
            return false;
        }
        $departmentUser = $identity->department;
        if ($departmentUser == $modelTikTok->departmentModel->department_name) {
            return false;
        }
        return true;        
    }

}
