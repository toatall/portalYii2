<?php

namespace app\models\regecr;

use app\behaviors\AuthorBehavior;
use Yii;
use yii\db\Expression;
use app\models\Organization;

/**
 * This is the model class for table "{{%reg_ecr}}".
 *
 * @property int $id
 * @property string $code_org
 * @property string $date_reg
 * @property int $count_create
 * @property int $count_vote
 * @property int $avg_eval_a_1_1
 * @property int $avg_eval_a_1_2
 * @property int $avg_eval_a_1_3
 * @property string $author
 * @property string $date_create
 * @property string $date_update
 * @property string|null $date_delete
 *
 * @property Organization $codeOrg
 */
class RegEcr extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%reg_ecr}}';
    }

    /**
     * @return string
     */
    public static function getModule()
    {
        return 'regecr';
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function behaviors()
    {
        return [
            AuthorBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code_org', 'date_reg', 'count_create', 'count_vote', 'avg_eval_a_1_1', 'avg_eval_a_1_2', 'avg_eval_a_1_3'], 'required'],
            [['date_reg', 'date_create', 'date_update', 'date_delete'], 'safe'],
            [['count_create', 'count_vote', 'avg_eval_a_1_1', 'avg_eval_a_1_2', 'avg_eval_a_1_3'], 'integer'],
            [['code_org'], 'string', 'max' => 5],
            [['author'], 'string', 'max' => 250],
            [['code_org'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['code_org' => 'code']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'code_org' => 'Наименование НО (по месту постановки на учет)',
            'date_reg' => 'Дата',
            'count_create' => 'Кол-во вновь созданных ООО',
            'count_vote' => 'Кол-во опрошенных',
            'avg_eval_a_1_1' => 'Средняя оценка А 1.1',
            'avg_eval_a_1_2' => 'Средняя оценка А 1.2',
            'avg_eval_a_1_3' => 'Средняя оценка А 1.3',
            'author' => 'Автор',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
        ];
    }

    /**
     * Gets query for [[CodeOrg]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCodeOrg()
    {
        return $this->hasOne(Organization::class, ['code' => 'code_org']);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!$this->isNewRecord) {
            $this->date_update = new Expression('getdate()');
        }
        return parent::beforeSave($insert);
    }
}
