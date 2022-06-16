<?php

namespace app\models\department;

use app\models\Access;
use Yii;
use app\models\Tree;
use app\models\Organization;
use app\models\User;
use yii\bootstrap\Html;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%department}}".
 *
 * @property int $id
 * @property int $id_tree
 * @property string $id_organization
 * @property string $department_index
 * @property string $department_name
 * @property int|null $use_card
 * @property int|null $general_page_type
 * @property int|null $general_page_id_tree
 * @property string $author
 * @property string|null $log_change
 * @property string $date_create
 * @property string $date_edit
 *
 * @property Tree $tree
 * @property Organization $organization
 * @property User $author0
 * @property DepartmentCard[] $departmentCards
 */
class Department extends \yii\db\ActiveRecord
{
    /**
     * Показывать только первую новость
     * @var integer
     */
    const GP_SHOW_FIRST_NEWS = 0;

    /**
     * Показывать новости из списка
     * @var integer
     */
    const GP_SHOW_NEWS_FROM_LIST = 1;

    /**
     * Показывать структуру отдела
     * @var integer
     */
    const GP_SHOW_STRUCT = 2;

    /**
     * Вид главной страницы отдела
     * @var array
     * @uses getTypeGeneralPage()
     */
    private $_typeGeneralPage = array(
        self::GP_SHOW_FIRST_NEWS => 'Отображать первую новость',
        self::GP_SHOW_NEWS_FROM_LIST => 'Показать новость из списка',
        self::GP_SHOW_STRUCT => 'Показать структуру отдела',
    );

    /**
     * Дополнительные настройки прав
     * @var boolean
     */
    public $useOptionalAccess = false;

    /**
     * @var array
     */
    public $permissionUser;

    /**
     * @var array
     */
    public $permissionGroup;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%department}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tree', 'id_organization', 'department_index', 'department_name'], 'required'],
            [['id_tree', 'use_card', 'general_page_type', 'general_page_id_tree'], 'integer'],
            [['log_change'], 'string'],
            [['date_create', 'date_edit'], 'safe'],
            [['id_organization'], 'string', 'max' => 5],
            [['department_index'], 'string', 'max' => 2],
            [['department_name', 'author'], 'string', 'max' => 250],
            [['id_tree'], 'exist', 'skipOnError' => true, 'targetClass' => Tree::className(), 'targetAttribute' => ['id_tree' => 'id']],
            [['id_organization'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::className(), 'targetAttribute' => ['id_organization' => 'code']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author' => 'username_windows']],
            [['permissionGroup', 'permissionUser'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_tree' => 'ИД структуры',
            'id_organization' => 'Код НО',
            'department_index' => 'Индекс отдела',
            'department_name' => 'Наименование отдела',
            'use_card' => 'Показывать структуру отдела',
            'general_page_type' => 'Страница по умолчанию',
            'general_page_id_tree' => 'Выбрать новость',
            'author' => 'Автор',
            'log_change' => 'Журнал изменений',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'permissionUser' => 'Пользователи',
            'permissionGroup' => 'Группы',
            'concatened' => 'Отдел',
        ];
    }

    /**
     * Gets query for [[Tree]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTree()
    {
        return $this->hasOne(Tree::class, ['id' => 'id_tree']);
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
     * Gets query for [[DepartmentCards]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartmentCards()
    {
        return $this->hasMany(DepartmentCard::class, ['id_department' => 'id']);
    }

    /**
     * Для отображения отделов, к которым у пользвателя есть доступ
     * @return \yii\db\ActiveQuery
     */
    public static function backendFind()
    {
        $query = self::find()->alias('t');
        $query->distinct(true);
        $query->select('t.*')->orderBy('t.id');
        if (!Yii::$app->user->can('admin')) {
            if (Yii::$app->user->isGuest) {
                $query->where(['1' => 0]);
            }
            else {
                $query->leftJoin('{{%access_department_group}} access_department_group', 'access_department_group.id_department=t.id');
                $query->leftJoin('{{%access_department_user}} access_department_user', 'access_department_user.id_department=t.id');
                $query->leftJoin('{{%group_user}} group_user', 'group_user.id_group=access_department_group.id_group');
                $query->filterWhere(['or',
                    ['access_department_user.id_user' => Yii::$app->user->id],
                    ['group_user.id_user' => Yii::$app->user->id],
                ]);
            }
        }
        return $query;
    }

    /**
     * @param $pk
     * @return array|\yii\db\ActiveRecord|null
     */
    public static function backendFindByPk($pk)
    {
        return self::backendFind()->andWhere(['t.id'=>$pk])->one();
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!$this->isNewRecord) {
            $this->date_edit = new Expression('getdate()');
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \yii\db\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        Access::saveDepartmentGroups($this->id, $this->permissionGroup);
        Access::saveDepartmentUsers($this->id, $this->permissionUser);
        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * Возвращается индекс и наименование отдела
     * @return string
     */
    public function getConcatened()
    {
        return $this->department_index . ' ' . $this->department_name;
    }

    /**
     * Список групп, имеющих доструп к текущему отделу
     * @return array
     * @uses \app\modules\admin\controllers\DepartmentController::actionCreate()
     * @uses \app\modules\admin\controllers\DepartmentController::actionUpdate()
     */
    public function getPermissionGroups()
    {
        $query = new Query();
        $query->from('{{%access_department_group}} t_access_group')
            ->leftJoin('{{%group}} t_group', 't_access_group.id_group = t_group.id')
            ->where([
                't_access_group.id_department' => $this->id,
                't_access_group.id_organization' => \Yii::$app->userInfo->current_organization,
            ])
            ->select('t_group.id, t_group.name')
            ->orderBy('t_group.name asc');
        return ArrayHelper::map($query->all(), 'id' ,'name');
    }

    /**
     * Список пользователей, имеющих доструп к текущему отделу
     * @return array
     * @uses \app\modules\admin\controllers\DepartmentController::actionCreate()
     * @uses \app\modules\admin\controllers\DepartmentController::actionUpdate()
     */
    public function getPermissionUsers()
    {
        $query = new Query();
        $query->from('{{%access_department_user}} t_access_user')
            ->leftJoin('{{%user}} t_user', 't_access_user.id_user = t_user.id')
            ->where([
                't_access_user.id_department' => $this->id,
                't_access_user.id_organization' => \Yii::$app->userInfo->current_organization,
            ])
            ->select("t_user.id, t_user.username_windows + ' (' + t_user.fio + ')' [username]")
            ->orderBy('t_user.username_windows asc');
        return ArrayHelper::map($query->all(), 'id' ,'username');
    }

    /**
     * Меню отдела
     * @param null $idParent
     * @return array
     * @uses \app\controllers\DepartmentController::loadMenu()
     */
    public function getMenu($idParent=null)
    {
        $result = [];
        if ($idParent==null) {
            $idParent = $this->id_tree;
        }

        $query = new Query();
        $resultQuery = $query->from('{{%tree}}')
            ->where([
                'id_parent'=>$idParent,
                'date_delete'=>null,
            ])
            ->select('id, name, is_url, url')
            ->all();

        foreach ($resultQuery as $item) {
            $subItems = $this->getMenu($item['id']);

            if ($item['is_url']) {
                $url = Url::to($item['url']);
            }
            else {
                $url = ['department/view', 'id'=>$this->id, 'idTree'=>$item['id']];
            }

            $result[] = [
                'label' => $item['name'],
                'url' => $url,
                'items' => $subItems,
                'options' => $subItems ? ['class' => 'dropdown-submenu'] : [],
            ];
        }
        return $result;
    }


    /**
     * Подразделы отдела
     * @param int $idTree идентификатор структуры
     * @param int $idDepartment идентификатор отдела
     * @param boolean $flagStruct
     * @return string   
     */
    public function departmentTree($idTree, $idDepartment, $flagStruct=false)
    {
        $resultArr = [];

        $query = new Query();
        $resultQuery = $query
            ->from('{{%tree}}')
            ->where([
                'id_parent'=>$idTree,
                'date_delete'=>null,
            ])
            ->all();

        if (count($resultQuery)==0 && !$flagStruct && !$this->use_card) {
            return '';
        }
        if ($this->use_card && !$flagStruct) {
            $resultArr[] = [
                'id' => $idDepartment,
                'text' => 'Структура', 
                'href' => Url::to(['department/struct', 'id'=>$idDepartment]),
            ];
            $flagStruct = true;
        }

        if ($resultQuery !== null) {
            foreach ($resultQuery as $tree) {
                $item = [
                    'id' => $tree['id'],
                    'text' => $tree['name'],
                    'href' => Url::to(['department/view', 'id'=>$idDepartment, 'idTree'=>$tree['id']]),
                ];
                $subItems = $this->departmentTree($tree['id'], $idDepartment, $flagStruct);
                if ($subItems) {
                    $item['items'] = $subItems;
                }
                $resultArr[] =  $item;
            }
        }

        return $resultArr;
    }

    /**
     * Список отделов для select
     * @return array
     */
    public static function dropDownList()
    {
        $query = self::find()->all();
        return ArrayHelper::map($query, 'id', 'concatened');
    }
}
