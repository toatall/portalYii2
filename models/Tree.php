<?php

namespace app\models;

use app\helpers\Log\LogHelper;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\Access;
use yii\helpers\Url;

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
 *
 * @property AccessGroup[] $accessGroups
 * @property AccessUser[] $accessUsers
 * @property Department[] $departments
 * @property News[] $news
 * @property RatingMain[] $ratingMains
 * @property Telephone[] $telephones
 * @property Organization $organization
 * @property User $author0
 */
class Tree extends \yii\db\ActiveRecord
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
    public static function tableName()
    {
        return '{{%tree}}';
    }

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

    // /**
    //  * Gets query for [[AccessGroups]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getAccessGroups()
    // {
    //     return $this->hasMany(AccessGroup::className(), ['id_tree' => 'id']);
    // }

    // /**
    //  * Gets query for [[AccessUsers]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getAccessUsers()
    // {
    //     return $this->hasMany(AccessUser::className(), ['id_tree' => 'id']);
    // }

    // /**
    //  * Gets query for [[Departments]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getDepartments()
    // {
    //     return $this->hasMany(Department::className(), ['id_tree' => 'id']);
    // }

    // /**
    //  * Gets query for [[News]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getNews()
    // {
    //     return $this->hasMany(News::className(), ['id_tree' => 'id']);
    // }

    // /**
    //  * Gets query for [[RatingMains]].
    //  *
    //  * @return \yii\db\ActiveQuery
    //  */
    // public function getRatingMains()
    // {
    //     return $this->hasMany(RatingMain::className(), ['id_tree' => 'id']);
    // }

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
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuthor0()
    {
        return $this->hasOne(User::class, ['username_windows' => 'author']);
    }

    /**
     * Мадуль по-умолчанию
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
        $query = static::find()->where($condition);
        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['date_delete' => null]);
        }
        return $query->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function findPublic()
    {
        return static::find()->where(['date_delete'=>null]);
    }

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

        if ($this->isNewRecord) {
            $this->date_create = new Expression('getdate()');
            $this->author = Yii::$app->user->identity->username;
        }

        $this->log_change = LogHelper::setLog($this->log_change, ($this->isNewRecord ? 'создание' : 'изменение'));

        $this->id_organization = Yii::$app->userInfo->current_organization;

        if ($this->allOrganization) {
            $this->id_organization = '0000';
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
        Access::saveTreeGroups($this->id, $this->permissionGroup, $this->useParentRight);
        Access::saveTreeUsers($this->id, $this->permissionUser, $this->useParentRight);
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
    public static function getTreeForMain($id=0, $parent_id=0)
    {
        $treeQuery = Tree::find()
            ->where(['id_parent' => $parent_id])
            ->andWhere(['<>', 'id', $id])
            ->andWhere(['in', 'id_organization', [Yii::$app->userInfo->current_organization, '0000']]);

        if (!Yii::$app->user->can('admin')) {
            $treeQuery->andWhere(['date_delete' => null]);
        }

        $treeQuery->orderBy('sort asc, name asc, date_create asc');

        $orgData = $treeQuery->all();
        $data = ''; $resultLi = '';

        foreach ($orgData as $value) {
            if (Yii::$app->user->can('admin') || Access::checkAccessUserForTree($value->id)) {
                $urlView = $value['module'] == '' ? '#' : Url::to(['/admin/' . $value['module'] . '/index', 'idTree'=>$value['id']]);
                $urlUpdate = Url::to(['/admin/tree/update', 'id'=>$value['id']]);
                $urlDelete = Url::to(['/admin/tree/delete', 'id'=>$value['id']]);
                $nodeName = $value['name'];
                $isDelete = $value['date_delete'] != null;

                $node = $value['module'] == '' ? $value['name']
                    : Html::a($value['name'], [$value['module'] . '/index', 'idTree'=>$value['id']], ['class' => $isDelete ? 'text-danger' : '']);
                $resultLi .= '<li data-url-view="' . $urlView . '" data-url-update="' . $urlUpdate . '" data-url-delete="' . $urlDelete . '" data-node-name="' . $nodeName . '">'
                    . '<span ' . ($isDelete ? 'class="text-danger"' : '') . '>' . $node . '</span>'
                    . self::getTreeForMain($id, $value['id']) . '</li>';
            }
            else {
                $data .= self::getTreeForMain($id, $value['id']);
            }
        }

        if ($resultLi <> '') {
            $data .= '<ul>' . $resultLi . '</ul>';
        }

        return $data;
    }

    /**
     * Дерево структуры для DropDownList
     * @param int $id идентификатор структуры
     * @param int $parent_id идентификатор родителя
     * @param number $level уровень
     * @see Tree
     * @return array|number
     */
    public static function getTreeDropDownList($id=0, $parent_id=0, $level=1)
    {
        $query = new Query();
        $query->from('{{%tree}}')
            ->where([
                'id_parent' => $parent_id,
                'id_organization' => ['0000', \Yii::$app->userInfo->current_organization],
                'disable_child' => 0,
            ])
            ->andWhere(['<>', 'id', $id]);
        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['date_delete' => null]);
        }
        $query->orderBy('sort asc, name asc, date_create asc');


        $result = [];
        $resultQuery = $query->all();
        foreach ($resultQuery as $item)
        {
            if (Yii::$app->user->can('admin') || Access::checkAccessUserForTree($item['id'])) {
                $row = array($item['id'] => str_repeat('--', $level) . ' ' . $item['name']);
                $flagLevel = 1;
            }
            else
            {
                $row = [];
                $flagLevel = 0;
            }
            $result = $result + $row + self::getTreeDropDownList($id, $item['id'], $level + $flagLevel);
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
                't_access_group.id_organization' => \Yii::$app->userInfo->current_organization,
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
                't_access_user.id_organization' => \Yii::$app->userInfo->current_organization,
            ])
            ->select("t_user.id, t_user.username_windows + ' (' + t_user.fio + ')' [username]")
            ->orderBy('t_user.username_windows asc');
        return ArrayHelper::map($query->all(), 'id' ,'username');
    }


}
