<?php

namespace app\modules\paytaxes\models;

use app\behaviors\DatetimeBehavior;
use app\models\Organization;
use Yii;

/**
 * This is the model class for table "{{%pay_taxes_chart_month}}".
 *
 * @property int $id
 * @property string $code_org
 * @property string $month
 * @property float|null $sum1
 * @property string|null $date_create
 * @property string $year
 *
 * @property Organization $codeOrg
 */
class PayTaxesChartMonth extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%pay_taxes_chart_month}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code_org', 'month'], 'required'],
            [['sum1'], 'number'],
            [['date_create'], 'safe'],
            [['code_org'], 'string', 'max' => 5],
            [['year'], 'string', 'max' => 4],
            [['month'], 'string', 'max' => 50],
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

    /**
     * Предыдущее значение в прошлом году
     * @return int|null
     */
    public function getValByYear($year)
    {
        $num = null;
        $query = self::find()->where([
            'month' => $this->month,
            'year' => $year,
            'code_org' => $this->code_org,
        ])->one();
        if ($query != null) {
            $num = $query['sum1'];
        }
        return $num;
    }


}
