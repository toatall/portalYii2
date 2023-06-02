<?php
namespace app\modules\meeting\models\ar;

use app\behaviors\AuthorBehavior;
use app\modules\meeting\models\traits\AttributesTrait;
use app\modules\meeting\models\traits\RelationsTrait;
use yii\behaviors\TimestampBehavior;

/**
 * Базовый класс взаимодействия с базой данных
 * 
 * @property int $id
 * @property string $type
 * @property string $org_code
 * @property string $theme
 * @property int|string $date_start
 * @property int|string $duration
 * @property string $place
 * @property string $note
 * @property string $members_people
 * @property string $members_organization
 * @property string $responsible
 * 
 * @property int $date_create
 * @property int $date_update
 * @property int $date_delete
 * @property string $author
 * 
 * @author taotall
 */
abstract class ARMeeting extends \yii\db\ActiveRecord
{
    use AttributesTrait, RelationsTrait;
    
    /**
     * @inheritDoc
     */
    public static function tableName(): string
    {
        return '{{%meeting}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'Идентификатор',
            'org_code' => 'Код НО',
            'type' => 'Вид',
            'theme' => 'Тема',            
            'date_start' => 'Дата и время начала',
            'date_start_str' => 'Дата и время начала',
            'time_start' => 'Время начала',           
            'place' => 'Место проведения',           
            'responsible' => 'Ответственные',
            'members_people' => 'Участники (сотрудники Управления)',
            'members_organization' => 'Участники (Инспекции)',
            'duration' => 'Продолжительность',
            'duration_str' => 'Продолжительность',
            'duration_as_duration' => 'Продолжительность',
            'note' => 'Примечание',            
                       
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
            'author' => 'Автор',
        ];
    }    

    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_update',
            ],
            [
                'class' => AuthorBehavior::class,
            ],
        ];
    }

    

}