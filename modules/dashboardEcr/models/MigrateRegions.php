<?php

namespace app\modules\dashboardEcr\models;

use Yii;
use yii\db\Expression;
use yii\db\Query;

/**
 * This is the model class for table "{{%migrate_regions}}".
 *
 * @property int $id
 * @property string $reg_code
 * @property float|null $count_in
 * @property float|null $count_out
 * @property string $date_create
 * @property string|null $date_update
 *
 */
class MigrateRegions extends \yii\db\ActiveRecord
{

    public $regionName;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%migrate_regions}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['reg_code', 'date_create'], 'required'],
            [['count_in', 'count_out'], 'number'],
            [['count_in', 'count_out'], 'default', 'value' => 0],
            [['date_create', 'date_update'], 'safe'],
            [['reg_code'], 'string', 'max' => 2],            
        ];
    }

    
    public static function findOrCreate($regionCode, $regName)
    {
        /** @var MigrateRegions $model */
        $model = self::find()->where(['reg_code' => $regionCode])->one();
        if ($model === null) {
            $model = new self([
                'reg_code' => $regionCode,
                'date_create' => new Expression('getdate()'),
            ]);
        }
        else {
            $model->date_update = new Expression('getdate()');
        }
        $model->regionName = $regName;
        return $model;
    }    

}
