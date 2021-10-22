<?php

namespace app\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%organization}}".
 *
 * @property string $code
 * @property string $name
 * @property int|null $sort
 * @property string $date_create
 * @property string $date_edit
 * @property string $name_short
 * @property string $date_end
 *
 * @property Department[] $departments
 * @property File[] $files
 * @property Group[] $groups
 * @property News[] $news
 * @property Telephone[] $telephones
 * @property Tree[] $trees
 * @property UserOrganization[] $userOrganizations
 * @property string $fullName
 */
class Organization extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%organization}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name', 'sort'], 'required'],
            [['sort'], 'integer'],
            [['date_create', 'date_edit', 'date_end'], 'safe'],
            [['code'], 'string', 'max' => 5],
            [['name'], 'string', 'max' => 250],
            [['name_short'], 'string', 'max' => 200],
            [['code'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'code' => 'Код',
            'name' => 'Наименование',
            'name_short' => 'Краткое наименование',
            'sort' => 'Сортировка',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'date_end' => 'Дата окончания',
        ];
    }

    /**
     * Gets query for [[Departments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDepartments()
    {
        return $this->hasMany(Department::className(), ['id_organization' => 'code']);
    }

    /**
     * Gets query for [[Files]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getFiles()
    {
        return $this->hasMany(File::className(), ['id_organization' => 'code']);
    }

    /**
     * Gets query for [[Groups]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getGroups()
    {
        return $this->hasMany(Group::className(), ['id_organization' => 'code']);
    }

    /**
     * Gets query for [[News]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNews()
    {
        return $this->hasMany(News::className(), ['id_organization' => 'code']);
    }

    /**
     * Gets query for [[Telephones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTelephones()
    {
        return $this->hasMany(Telephone::className(), ['id_organization' => 'code']);
    }

    /**
     * Gets query for [[Trees]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrees()
    {
        return $this->hasMany(Tree::className(), ['id_organization' => 'code']);
    }

    /**
     * Gets query for [[UserOrganizations]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOrganizations()
    {
        return $this->hasMany(UserOrganization::className(), ['id_organization' => 'code']);
    }
    
    /**
     * Список организаций для списков
     * @return type
     */
    public static function getDropDownList($onlyIfns = false, $withNull = false)
    {
        $query = self::find();
        if ($onlyIfns) {
            $query->where(['<>', 'code', '8600']);
        }
        $result = \yii\helpers\ArrayHelper::map($query->all(), 'code', 'fullName');
        if ($withNull) {
            $result = ArrayHelper::merge([''=>''], $result);
        }
        return $result;
    }
    
    
    /**
     * Наименование организации с кодом
     * @return string
     */
    public function getFullName()
    {
        return $this->name . ' (' . $this->code . ')';
    }

    /**
     * Получение текущей орагнизации (User.current_organization) для текущего пользователя
     * Если у пользователя по какой-либо причине отсутсвует доступ
     * к текущей организации, то выбирается первая доступная пользователю организации
     * @author toatall
     * @see User
     */
    public static function loadCurrentOrganization()
    {
        // если уже присвоен код организации, то выходим
        if (isset(\Yii::$app->userInfo->current_organization)) return;

        // выполняем поиск текущего пользователя
        $userModel = Yii::$app->user->identity; //User::model()->findByPk(Yii::app()->user->id);

        $userCurrentOrganization = isset($userModel->current_organization) && !empty($userModel->current_organization)
            ? $userModel->current_organization : null;

        // проверка прав у пользователя к текущей организации
        if (!User::checkRightOrganization($userCurrentOrganization))
        {
            $userCurrentOrganization = null;
        }
        else
        {
            Yii::$app->userInfo->current_organization = $userCurrentOrganization;
        }

        // если нет доступа к текущей организации
        if ($userCurrentOrganization===null)
        {
            $organizations = $userModel->organizations;
            if ($organizations != null)
            {
                if (isset($userModel->organization[0]->code))
                {
                    $userCurrentOrganization = $userModel->organization[0]->code;
                    User::changeOrganization($userCurrentOrganization);
                }
            }
        }
    }
}
