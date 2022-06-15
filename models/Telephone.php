<?php

namespace app\models;

use app\behaviors\ChangeLogBehavior;
use Yii;
use yii\db\Expression;
use yii\db\Query;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%telephone}}".
 *
 * @property int $id
 * @property int $id_tree
 * @property string $id_organization
 * @property string $telephone_file
 * @property string|null $dop_text
 * @property int|null $sort
 * @property string $date_create
 * @property string $date_edit
 * @property string $author
 * @property string|null $log_change
 * @property int|null $count_download
 *
 * @property Tree $tree
 * @property Organization $organization
 * @property User $author0
 * @property TelephoneDownload[] $telephoneDownloads
 */
class Telephone extends \yii\db\ActiveRecord
{

    /**
     * Файл справочника
     * @var UploadedFile
     */
    public $uploadFile;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%telephone}}';
    }

    public static function getModule()
    {
        return 'telephone';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            [
                'class' => ChangeLogBehavior::class,
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_tree', 'id_organization', 'author'], 'required'],
            [['id_tree', 'sort', 'count_download'], 'integer'],
            [['date_create', 'date_edit'], 'safe'],
            [['log_change'], 'string'],
            [['id_organization'], 'string', 'max' => 5],
            [['telephone_file', 'dop_text', 'author'], 'string', 'max' => 250],
            [['id_tree'], 'exist', 'skipOnError' => true, 'targetClass' => Tree::class, 'targetAttribute' => ['id_tree' => 'id']],
            [['id_organization'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['id_organization' => 'code']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],
            [['uploadFile'], 'file', 'skipOnEmpty' => true],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'УН',
            'id_tree' => 'Раздел',
            'id_organization' => 'Код НО',
            'telephone_file' => 'Файл телефонного справочника',
            'dop_text' => 'Примечание',
            'sort' => 'Сортировка',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'author' => 'Автор',
            'log_change' => 'История изменений',
            'count_download' => 'Количество загрузок',
        ];
    }

    /**
     * Вывод списка справочников для backend
     * @param $idTree
     * @return \yii\db\ActiveQuery
     */
    public static function findBackend($idTree)
    {
        if (Yii::$app->user->isGuest) {
            return;
        }

        $query = static::find()
            ->alias('t')
            ->distinct(true)
            ->select('t.*')
            ->leftJoin('{{%organization}} organization', 't.id_organization=organization.code')
            ->where(['t.id_tree'=>$idTree])
            ->orderBy('t.id_organization asc');

        if (!Yii::$app->user->can('admin')) {

            $idUser = Yii::$app->user->id;

            // подключение привязанных к узлу групп
            $query->leftJoin('{{%access_group}} access_group', 'access_group.id_tree=t.id_tree')
                  ->leftJoin('{{%group_user}} group_user', 'group_user.id_group=access_group.id_group');

            // подключение привязанных к узлу пользователей
            $query->leftJoin('{{%access_user}} access_user', 'access_user.id_tree=t.id_tree');

            // подключение привязанных к группам и пользователям организаций
            $query->leftJoin('{{%access_organization_group}} access_organization_group', 'access_organization_group.id_access_group=access_group.id_group')
                  ->leftJoin('{{%access_organization_user}} access_organization_user', 'access_organization_user.id_access_user=access_user.id_user');

            $query->andWhere('(group_user.id_user=:user1 and access_organization_group.id_organization=t.id_organization) or 
                (access_user.id_user=:user2 and access_organization_user.id_organization=t.id_organization)
            ', [
                ':user1' => $idUser,
                ':user2' => $idUser,
            ]);

        }

        return $query;
    }

    /**
     * Список доступных для пользователя организаций
     * (по которым он может вносить изменения)
     * @return array
     */
    public function getOrganizations()
    {
        $query = new Query();
        $query->from('{{%organization}} t')
            ->select('code, name')
            ->distinct(true);

        if (!Yii::$app->user->can('admin')) {

            $idUser = Yii::$app->user->id;

            // подключение привязанных к узлу групп
            $query->leftJoin('{{%access_group}} access_group', 'access_group.id_tree=:id_tree1', [':id_tree1'=>$this->id_tree])
                ->leftJoin('{{%group_user}} group_user', 'group_user.id_group=access_group.id_group');

            // подключение привязанных к узлу пользователей
            $query->leftJoin('{{%access_user}} access_user', 'access_user.id_tree=:id_tree2', ['id_tree2'=>$this->id_tree]);

            // подключение привязанных к группам и пользователям организаций
            $query->leftJoin('{{%access_organization_group}} access_organization_group', 'access_organization_group.id_access_group=access_group.id_group')
                  ->leftJoin('{{%access_organization_user}} access_organization_user', 'access_organization_user.id_access_user=access_user.id_user');

            $query->andWhere('(group_user.id_user=:user1 and access_organization_group.id_organization=t.code) or 
                (access_user.id_user=:user2 and access_organization_user.id_organization=t.code)
            ', [
                ':user1' => $idUser,
                ':user2' => $idUser,
            ]);
        }

        $query->orderBy('code asc');
        return $query->all();
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
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->date_edit = new Expression('getdate()');

        // загрузка файла
        $this->upload();

        return true;
    }

    /** {@inheritDoc}
     * @return bool
     */
    public function beforeValidate()
    {
        $this->author = Yii::$app->user->identity->username;
        //$this->id_organization = Yii::$app->userInfo->current_organization;
        return parent::beforeValidate();
    }

    /**
     * Загрузка файла
     */
    private function upload()
    {
        if ($this->uploadFile && $this->uploadFile instanceof \yii\web\UploadedFile) {
            // если уже загружался другой справочник, то удаляем его
            if (!$this->isNewRecord && $this->telephone_file) {
                // если имя такое же, то оставляем, просто заменим,
                // т.к. при удалении и загрузке нового изображения - удаляется новое (видимо операция удаления медленнее, чем загрузки)
                if (basename($this->telephone_file) != basename($this->uploadFile->name)) {
                    \Yii::$app->storage->deleteFile($this->telephone_file);
                }
            }

            $path = $this->getPath();
            \Yii::$app->storage->saveUploadedFile($this->uploadFile, $path);
            $this->telephone_file = $path . $this->uploadFile->name;
        }
    }

    /**
     * Удаление файла
     * @return bool
     */
    protected function deleteUpload()
    {
        if ($this->telephone_file != null) {
            return \Yii::$app->storage->deleteFile($this->telephone_file);
        }
    }

    /**
     * Папка с файлами
     * @return string
     */
    private function getPath()
    {
        return Yii::$app->params['telephone']['path'];
    }

}
