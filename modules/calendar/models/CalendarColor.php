<?php

namespace app\modules\calendar\models;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\models\User;
use Yii;

/**
 * This is the model class for table "{{%calendar_color}}".
 *
 * @property int $id
 * @property string $date
 * @property string $org_code
 * @property string|null $color
 * @property string $date_create
 * @property string|null $date_update
 * @property string $author
 *
 * @property User $author0
 */
class CalendarColor extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%calendar_color}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [            
            [
                'class' => DatetimeBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
            ['class' => AuthorBehavior::class],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date', 'org_code'], 'required'],
            [['date', 'date_create', 'date_update'], 'safe'],
            [['org_code'], 'string', 'max' => 5],
            [['color'], 'string', 'max' => 50],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'date' => 'Date',
            'org_code' => 'Org Code',
            'color' => 'Color',
            'date_create' => 'Date Create',
            'date_update' => 'Date Update',
            'author' => 'Author',
        ];
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['username' => 'author']);
    }

    /**
     * Дата с оформленным цветом фона
     * @return string
     */
    public function getDisplayDateWithColor()
    {
        $classColor = $this->color ? $this->color : '';
        return '<span class="badge badge-'. $classColor . '">' . $this->date . '</span>';
    }

    /**
     * {@inheritdoc}
     */
    public function afterFind()
    {
        $this->date = Yii::$app->formatter->asDate($this->date) ?? null;
        return parent::afterFind();
    }

}
