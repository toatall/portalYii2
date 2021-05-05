<?php

namespace app\modules\test\models;

use Yii;

/**
 * This is the model class for table "{{%test_result_opinion}}".
 *
 * @property int $id
 * @property int $id_test
 * @property int $rating
 * @property string|null $note
 * @property string $author
 * @property string|null $date_create
 *
 */
class TestResultOpinion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%test_result_opinion}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_test', 'rating', 'author'], 'required'],
            [['id_test', 'rating'], 'integer'],
            [['note'], 'string'],
            [['date_create'], 'safe'],
            [['author'], 'string', 'max' => 250],
            [['id_test'], 'exist', 'skipOnError' => true, 'targetClass' => Test::class, 'targetAttribute' => ['id_test' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_test' => 'Id Test',            
            'rating' => 'Rating',
            'note' => 'Note',
            'author' => 'Author',
            'date_create' => 'Date Create',
        ];
    }
    
    /**
     * {@inheritdoc}     
     */    
    public function beforeValidate() 
    {
        $this->author = Yii::$app->user->identity->username;
        return parent::beforeValidate();
    }
}
