<?php

namespace app\modules\paytaxes\models;

use app\behaviors\DatetimeBehavior;
use app\models\Organization;
use Yii;

/**
 * This is the model class for table "{{%pay_taxes_general}}".
 *
 * @property int $id
 * @property string $code_org
 * @property string $date
 * @property float|null $sum1
 * @property float|null $sum2
 * @property float|null $sum3
 * @property float|null $sms
 * @property string|null $date_create
 * @property float|null $sms_1
 * @property float|null $sms_2
 * @property float|null $sms_3
 * @property float|null $sum_left_all
 * @property float|null $sum_left_nifl
 * @property float|null $sum_left_tn
 * @property float|null $sum_left_zn
 * @property float|null $growth_sms
 * @property int|null $kpe_persent
 *
 * @property Organization $codeOrg
 */
class PayTaxesGeneral extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pay_taxes_general}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code_org', 'date'], 'required'],
            [['date', 'date_create'], 'safe'],
            [['sum1', 'sum2', 'sum3', 'sms', 'sms_1', 'sms_2', 'sms_3', 'sum_left_all', 
                'sum_left_nifl', 'sum_left_tn', 'sum_left_zn', 'growth_sms', 'kpe_persent'], 'number'],
            [['code_org'], 'string', 'max' => 5],
            [['code_org'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 
                'targetAttribute' => ['code_org' => 'code']],
        ];
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
                'updatedAtAttribute' => null,
            ],
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

}
