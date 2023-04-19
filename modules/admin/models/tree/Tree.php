<?php

namespace app\modules\admin\models\tree;

use app\behaviors\AuthorBehavior;
use app\behaviors\DatetimeBehavior;
use app\helpers\DateHelper;
use app\helpers\Log\LogHelper;
use app\models\User;
use Yii;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;


/**
 * 
 * @property AccessGroup[] $accessGroups
 * @property AccessUser[] $accessUsers
 * @property Department[] $departments
 * @property News[] $news
 * @property RatingMain[] $ratingMains
 * @property Telephone[] $telephones
 * @property Organization $organization
 */
class Tree extends \app\models\Tree
{
    /**
     * Доступ пользователей к узлу
     * @var array
     */
    public $permissionUser;

    /**
     * Доступ групп к узлу
     * @var array
     */
    public $permissionGroup;

    /**
     * Галочка, которая отвечает за наследование прав
     * @var bool
     */
    public $useParentRight;

    /**
     * Предназначен ли данный узел для всех орагнизаций
     * @var bool
     */
    public $allOrganization;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_parent', 'use_organization', 'use_material', 'use_tape', 'sort', 'disable_child', 'useParentRight'], 'integer'],
            [['name'], 'required'],
            [['log_change'], 'string'],
            [['date_create', 'date_edit', 'date_delete'], 'safe'],
            [['id_organization'], 'string', 'max' => 5],
            [['name', 'author'], 'string', 'max' => 250],
            [['module', 'alias', 'view_static'], 'string', 'max' => 50],
            [['param1'], 'string', 'max' => 100],
            [['module'], 'ruleModuleExists'],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],
            [['permissionGroup', 'permissionUser'], 'safe'],
            [['is_url'], 'boolean'],
            [['url'], 'string', 'max' => 500],
        ];
    }

    /**
     * ПРАВИЛО. Проверка использования модуля более 1 раза
     * Только для модулей, который необходимо использовать 1 раз
     * @param string $attribute
     */
    public function ruleModuleExists($attribute)
    {
        $query = new Query();
        $query->from('{{%module}} module')
            ->leftJoin('{{%tree}} tree', 'tree.module=module.name')
            ->select('tree.name')
            ->where([
                'module.name' => $this->module,
                'module.only_one' => 1,
            ])
            ->andWhere(['<>', 'tree.id', ($this->isNewRecord ? 0 : $this->id)]);
        $row = $query->one();

        if ($row!=null) {
            $this->addError($attribute, 'Модуль уже используется в разделе "'.$row['name'].'"');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_parent' => 'ИД родителя',
            'id_organization' => 'Организация',
            'name' => 'Наименование',
            'module' => 'Модуль',
            'use_organization' => 'Группировка по справочнику организации',
            'use_material' => 'Размещать метериалы в этом разделе',
            'use_tape' => 'Использовать ленту',
            'sort' => 'Сортровка',
            'author' => 'Автор',
            'log_change' => 'Журнал изменений',
            'param1' => 'ИД ссылки (для модуля page)',
            'disable_child' => 'Запретить создание подразделов',
            'alias' => 'Алиас',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
            'organization' => 'Налоговый орган',
            'permissionUser' => 'Пользователи',
            'permissionGroup' => 'Группы',
            'useParentRight' => 'Добавить разрешения, наследуемые от родительских групп и пользователей',
            'allOrganization' => 'Для всех налоговых орнанов',
            'view_static' => 'Статистическая страница (только с модулем static)',
            'is_url' => 'Использование ссылки',
            'url' => 'Ссылка',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [            
            [
                'class' => DatetimeBehavior::class,
                'createdAtAttribute' => 'date_create',
                'updatedAtAttribute' => 'date_edit',
            ],
            ['class' => AuthorBehavior::class],     
        ];
    }    

    /**
     * Gets query for [[Telephones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTelephones()
    {
        return $this->hasMany(Telephone::class, ['id_tree' => 'id']);
    }

    /**
     * Gets query for [[Organization]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::class, ['code' => 'id_organization']);
    }    

    /**
     * Модуль по-умолчанию
     * @return string
     */
    public function getParamDefaultModule()
    {
        if (!Yii::$app->user->can('admin')) {
            return Yii::$app->params['tree']['defaultModule'];
        }
        return null;
    }

    /**
     * {@inheritDoc}
     * @param mixed $condition
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function findOne($condition)
    {
        $query = parent::find()->where($condition);
        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['date_delete' => null]);
        }
        return $query->one();
    }

    // /**
    //  * @return \yii\db\ActiveQuery
    //  */
    // public static function findPublic()
    // {
    //     return static::find()->where(['date_delete'=>null]);
    // }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        $this->log_change = LogHelper::setLog($this->log_change, ($this->isNewRecord ? 'создание' : 'изменение'));

        if ($this->allOrganization) {
            $this->id_organization = '0000';
        }
        else {
            $this->id_organization = Yii::$app->user->identity->current_organization;
        }

        return true;
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        if (Yii::$app->user->can('admin')) {
            // для пользователей с ролью администратора сохраняется все как указано на форме
            TreeAccess::assignGroupsPermissionsToNodeTree($this->id, $this->permissionGroup, $this->useParentRight);
            TreeAccess::assignUsersPermissionsToNodeTree($this->id, $this->permissionUser, $this->useParentRight);

        }
        else {
            // для пользователей без роли администратора сохранение родительских прав
            if ($insert) {                
                TreeAccess::assignGroupsPermissionsToNodeTree($this->id, null, true);
                TreeAccess::assignUsersPermissionsToNodeTree($this->id, null, true);
            }
        }
    }

    /**
     * {@inheritDoc}
     */
    public function afterFind()
    {
        $this->allOrganization = ($this->id_organization == '0000');
        parent::afterFind();
    }

    /**
     * Построение дерева структуры сайта на главной странице административной зоны
     * относительно текущего НО (Yii::app()->session['organization'])
     * @param int $id
     * @param int $parent_id
     * @return array
     */
    public static function generateJsonTree($items)
    {        
        if (!is_array($items)) {
            return [];
        }
        $result = [];
        foreach($items as $item) {
            
            if (isset($item['id'])) {
                $aClases = [];
                $prefix = '';
                $postfix = '';

                if (DateHelper::dateDiffDays($item['date_create']) <= 5) {
                    $postfix .= ' <span class="badge bg-success">Новое</span>';
                } 
                
                // если удален раздел (помечен как удален)
                if ($item['date_delete']) {
                    $aClases[] = 'text-danger';
                    $prefix .= '<span class="badge bg-danger" title="Дата удаления '. Yii::$app->formatter->asDatetime($item['date_delete']) .'">Запись удалена</span> ';
                }
                // если присвоен модуль
                elseif ($item['module']) {
                    $aClases[] = 'text-primary';
                    $postfix .= ' <i class="fas fa-link small"></i>';
                }

                $result[] = [                
                    'idNode' => $item['id'],
                    'text' => $prefix . $item['name'] . $postfix,
                    'name' => $item['name'],
                    'url' => $item['module'] == '' ? '' : Url::to(['/admin/' . $item['module'] . '/index', 'idTree'=>$item['id']]),
                    'urlUpdate' => Url::to(['/admin/tree/update', 'id'=>$item['id']]),
                    'urlDelete' => Url::to(['/admin/tree/delete', 'id'=>$item['id']]),
                    'isDeleted' => $item['date_delete'] != null,
                    'children' => self::generateJsonTree($item['childrens'] ?? null),
                    'a_attr' => [
                        'href' => $item['module'] == '' ? '#' : Url::to(['/admin/' . $item['module'] . '/index', 'idTree'=>$item['id']]),
                        'class' => implode(' ', $aClases),
                    ],
                ];
            }
            else {
                $result[] = self::generateJsonTree($item['childrens'] ?? null);
            }
            
        }
        return $result;
    }

    /**
     * Генерирование структуры дерева для выпадающего списка
     * @param array $items
     * @param int $level
     * @return array
     */
    public static function generateDropDownTree($items, $level = 0)
    {
        if (!is_array($items)) {
            return [];
        }
        $result = [];
        foreach($items as $item) {
            if ($item['disable_child']) {
                continue;
            }
            $row = [$item['id'] => str_repeat('--', $level) . ' ' . $item['name']];
            $result = ArrayHelper::merge($result, $row, self::generateDropDownTree($item['childrens'] ?? [], $level + 1));            
        }
        return $result;
    }

    
    /**
     * Список групп, имеющих доструп к текущему разделу
     * @return array
     * @uses \app\modules\admin\controllers\TreeController::actionCreate()
     * @uses \app\modules\admin\controllers\TreeController::actionUpdate()
     */
    public function getPermissionGroups()
    {
        $query = new Query();
        $query->from('{{%access_group}} t_access_group')
            ->leftJoin('{{%group}} t_group', 't_access_group.id_group = t_group.id')
            ->where([
                't_access_group.id_tree' => $this->id,
                't_access_group.id_organization' => \Yii::$app->user->identity->current_organization,
            ])
            ->select('t_group.id, t_group.name')
            ->orderBy('t_group.name asc');
        return ArrayHelper::map($query->all(), 'id' ,'name');
    }

    /**
     * Список пользователей, имеющих доструп к текущему разделу
     * @return array
     * @uses \app\modules\admin\controllers\TreeController::actionCreate()
     * @uses \app\modules\admin\controllers\TreeController::actionUpdate()
     */
    public function getPermissionUsers()
    {
        $query = new Query();
        $query->from('{{%access_user}} t_access_user')
            ->leftJoin('{{%user}} t_user', 't_access_user.id_user = t_user.id')
            ->where([
                't_access_user.id_tree' => $this->id,
                't_access_user.id_organization' => \Yii::$app->user->identity->current_organization,
            ])
            ->select("t_user.id, t_user.username_windows + ' (' + t_user.fio + ')' [username]")
            ->orderBy('t_user.username_windows asc');
        return ArrayHelper::map($query->all(), 'id' ,'username');
    }

}