<?php

namespace app\models\mentor;

use app\helpers\UploadHelper;
use Yii;
use app\models\User;
use app\models\Organization;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%mentor_post}}".
 *
 * @property int $id
 * @property int $id_mentor_ways
 * @property int $id_organization
 * @property string $title
 * @property string $message1
 * @property string $date_create
 * @property string $date_update
 * @property string|null $date_delete
 * @property string $author
 * @property int|null $count_like
 * @property int|null $count_comment
 * @property int|null $count_visit
 *
 * @property MentorWays $mentorWays
 * @property User $modelAuthor
 * @property MentorPostFiles[] $mentorPostFiles
 * @property MentorPostLike[] $mentorPostLikes
 * @property MentorPostVisit[] $mentorPostVisits
 * @property Organization $organization
 */
class MentorPost extends \yii\db\ActiveRecord
{

     /**
     * Файлы
     * @var UploadedFile[]
     */
    public $uploadFiles;

    /**
     * Файлы отмеченные для удаления
     * @var int[]
     */
    public $deleteFiles;


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%mentor_post}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_mentor_ways', 'id_organization', 'title', 'message1', 'author'], 'required'],
            [['id_mentor_ways', 'id_organization', 'count_like', 'count_comment', 'count_visit'], 'integer'],
            [['message1'], 'string'],
            [['date_create', 'date_update', 'date_delete'], 'safe'],
            [['title'], 'string', 'max' => 500],
            [['author'], 'string', 'max' => 250],
            [['id_mentor_ways'], 'exist', 'skipOnError' => true, 'targetClass' => MentorWays::className(), 'targetAttribute' => ['id_mentor_ways' => 'id']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['author' => 'username_windows']],
            [['uploadFiles'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 30],
            [['deleteFiles'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'id_mentor_ways' => 'Направление',
            'id_organization' => 'Организация',
            'title' => 'Заголовок',
            'message1' => 'Текст',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата измненения',
            'date_delete' => 'Дата удаления',
            'author' => 'Автор',
            'count_like' => 'Count Like',
            'count_comment' => 'Count Comment',
            'count_visit' => 'Count Visit',
            'uploadFiles' => 'Файлы',
            'deleteFiles' => 'Отметьте файлы для удаления',
            'uploadImages' => 'Изображения',
        ];
    }

    /**
     * Gets query for [[MentorWays]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMentorWays()
    {
        return $this->hasOne(MentorWays::className(), ['id' => 'id_mentor_ways']);
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getModelAuthor()
    {
        return $this->hasOne(User::class, ['username_windows' => 'author']);
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
     * Gets query for [[MentorPostFiles]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMentorPostFiles($files=[])
    {
        $model = $this->hasMany(MentorPostFiles::class, ['id_mentor_post' => 'id']);
        if (is_array($files) && count($files) > 0) {
            $model->where(['in', 'id', $files]);
        }
        return $model;
    }

    /**
     * Gets query for [[MentorPostLikes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMentorPostLikes()
    {
        return $this->hasMany(MentorPostLike::class, ['id_mentor_post' => 'id']);
    }

    /**
     * Gets query for [[MentorPostVisits]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMentorPostVisits()
    {
        return $this->hasMany(MentorPostVisit::class, ['id_mentor_post' => 'id']);
    }

    /**
     * @return array
     */
    public function getCheckListBoxUploadFilesGallery()
    {
        return ArrayHelper::map($this->getMentorPostFiles()->all(), 'id', 'filename');
    }

    /**
     * Является ли пользователь модератором (или администратором)
     * @return bool
     */
    public static function isModerator()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        if (Yii::$app->user->can('admin')) {
            return true;
        }
        return (new Query())
            ->from('{{%mentor_role_assign}}')
            ->where([
                'username' => Yii::$app->user->identity->username,
                'role_name' => 'moderator',
            ])
            ->exists();
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Throwable
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // удаление выбранных файлов
        if (is_array($this->deleteFiles) && count($this->deleteFiles) > 0) {
            $this->deleteUploadFiles($this->deleteFiles);
        }

        // Загрузка файлов
        $this->uploadFiles();
    }

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function beforeValidate()
    {
        $this->author = Yii::$app->user->identity->username;
        return parent::beforeValidate();
    }

    /**
     * Загрузка файлов
     */
    private function uploadFiles()
    {
        if (is_array($this->uploadFiles) && count($this->uploadFiles) > 0) {
            $helper = new UploadHelper($this->getPathFiles());
            $helper->uploadFiles($this->uploadFiles, function ($file, $saveFile, $path) {
                // сохранение в БД
                $fileModel = new MentorPostFiles();
                $fileModel->filename = \Yii::$app->storage->addEndSlash($path) . $file->name;
                $fileModel->id_mentor_post = $this->id;
                $fileModel->save();
            });
        }
    }

    /**
     * Каталог для загрузки файлов
     * @return string
     */
    protected function getPathFiles()
    {
        $path = \Yii::$app->params['mentor']['path']['files'];
        return $this->getPathWithReplace($path);
    }

    /**
     * Формирование пути для сохранения с подстановками (идентификатор)
     * @param string $path
     * @return string
     */
    protected function getPathWithReplace(string $path)
    {
        $path = str_replace('{id}', ($this->id ? $this->id : 'no_id'), $path);
        return $path;
    }

    /**
     * Удаление файлов
     * @param int[] $idFiles
     * @throws \Throwable
     * @uses \app\modules\admin\controllers\NewsController
     * @uses \app\models\news\News::afterSave()
     */
    protected function deleteUploadFiles($idFiles = [])
    {
        // получение идентификаторов файлов
        $files = $this->getMentorPostFiles($idFiles)->all();

        // удаление файлов с диска
        foreach ($files as $file) {
            $file->delete();
        }
    }

}
