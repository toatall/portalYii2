<?php

namespace app\models\menu;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use Yii;
use app\models\User;
use app\models\Access;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the model class for table "p_menu".
 *
 * @property int $id
 * @property int $id_parent
 * @property int $type_menu
 * @property string $name
 * @property string|null $link
 * @property string|null $submenu_code
 * @property string|null $target
 * @property int $blocked
 * @property int $sort_index
 * @property string|null $key_name
 * @property string $author
 * @property string $date_create
 * @property string $date_edit
 * @property string|null $log_change
 *
 * @property User $modelUser
 */
class Menu extends \yii\db\ActiveRecord
{
    const POSITION_MAIN = 1;
    const POSITION_LEFT = 2;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%menu}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parent', 'type_menu', 'name'], 'required'],
            [['id_parent', 'type_menu', 'blocked', 'sort_index'], 'integer'],
            [['submenu_code'], 'string'],
            [['key_name'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
            [['link'], 'string', 'max' => 500],
            [['target'], 'string', 'max' => 10],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_parent' => 'Родитель',
            'type_menu' => 'Тип меню',
            'name' => 'Наименование',
            'link' => 'Ссылка',
            'submenu_code' => 'Подменю',
            'target' => 'Аттрибут target',
            'blocked' => 'Блокировка',
            'sort_index' => 'Сортировка',
            'key_name' => 'Key Name',
            'author' => 'Автор',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'log_change' => 'Журнал изменений',
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
                'updatedAtAttribute' => 'date_edit',
            ],
            [
                'class' => AuthorBehavior::class,
                'author_at' => 'author',
            ],            
        ];
    }
    

    /**
     * Gets query for [[ModelUser]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModelUser()
    {
        return $this->hasOne(User::class, ['username_windows' => 'author']);
    }

    /**
     * Построение дерева меню сайта
     * @param int $id
     * @param int $parent_id
     * @param int $type_menu
     * @return string
     */
    public static function tree($type_menu, $id=0, $parent_id=0)
    {
        $query = new Query();
        $query->from('{{%menu}}')
            ->where([
                'id_parent' => $parent_id,
                'type_menu' => $type_menu,
            ])
            ->andWhere(['<>', 'id', $id]);
        $resultQuery = $query->all();

        $result = '';
        if (count($resultQuery)) {
            $result .= '<ul>';
        }
        foreach ($resultQuery as $item) {
            $urlUpdate = Url::to(['update', 'id'=>$item['id']]);
            $urlDelete = Url::to(['delete', 'id'=>$item['id']]);
            $result .= '<li data-url-update="' . $urlUpdate . '" data-url-delete="' . $urlDelete . '">'
                . $item['name'] . '&nbsp;&nbsp;&nbsp;' . self::tree($type_menu, $id, $item['id']) . '</li>';
        }
        if (count($resultQuery)) {
            $result .= '</ul>';
        }

        return $result;
    }

    /**
     * Дерево меню для DropDownList
     * @param int $type_menu тип меню (сверху, слева)
     * @param int $id идентификатор меню, которое не должно отображаться
     * @param int $parent_id идентификатор родительского меню
     * @param int $level уровень вложенности
     * @see CDbCriteria
     * @see Access
     * @return array
     */
    public function getMenuDropDownList($type_menu, $id=0, $parent_id=0, $level=1)
    {
        $query = new Query();
        $query->from('{{%menu}}')
            ->where([
                'id_parent' => $parent_id,
                'type_menu' => $type_menu,
            ])
            ->andWhere(['<>', 'id', $id]);
        $resultQuery = $query->all();

        $result = [];
        foreach ($resultQuery as $row)
        {
            $item = [$row['id'] => str_repeat('--', $level) . ' ' . $row['name']];
            $flagLevel = 1;
            $result = $result + $item + $this->getMenuDropDownList($type_menu, $id, $row['id'], $level + $flagLevel);
        }
        return $result;
    }

    /**
     * @return string
     */
    public static function topTree()
    {
        return self::tree(self::POSITION_MAIN);
    }

    /**
     * @return string
     */
    public static function leftTree()
    {
        return self::tree(self::POSITION_LEFT);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->sort_index = $this->sort_index == null ? 0 : $this->sort_index;
        return parent::beforeSave($insert);
    } 

    /**
     * {@inheritdoc}
     */
    public function afterDelete()
    {
        $this->deleteCache();
    }

    /**
     * {@inheritdoc}
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        $this->deleteCache();        
    }
    
    /**
     * Удаление кэша (после изменения или удаления записи)
     */
    private function deleteCache()
    {
        Yii::$app->cache->delete('menu_' . $this->type_menu);
    }

}
