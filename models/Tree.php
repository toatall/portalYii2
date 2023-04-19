<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%tree}}".
 *
 * @property int $id
 * @property int $id_parent
 * @property string $id_organization
 * @property string $name
 * @property string|null $module
 * @property int|null $use_organization
 * @property int|null $use_material
 * @property int|null $use_tape
 * @property int|null $sort
 * @property string $author
 * @property string|null $log_change
 * @property string|null $param1
 * @property int|null $disable_child
 * @property string|null $alias
 * @property string $view_static
 * @property string $date_create
 * @property string $date_edit
 * @property string|null $date_delete
 * @property boolean $is_url
 * @property string $url
 */
class Tree extends \yii\db\ActiveRecord
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%tree}}';
    }

    /**
     * Для получения списка узлов без пометки об удалении 
     * @return \yii\db\ActiveQuery
     */
    public static function findPublic()
    {
        return parent::find()->where(['date_delete'=>null]);
    }
    
}
