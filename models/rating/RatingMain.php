<?php

namespace app\models\rating;

use app\behaviors\AuthorBehavior;
use app\helpers\UploadHelper;
use app\models\File;
use Yii;
use app\models\User;
use app\models\Tree;
use yii\base\ErrorException;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%rating_main}}".
 *
 * @property int $id
 * @property int $id_tree
 * @property string $name
 * @property int|null $order_asc
 * @property string|null $note
 * @property string|null $log_change
 * @property string $date_create
 * @property string $author
 *
 * @property RatingData[] $ratingDatas
 * @property User $author0
 * @property Tree $tree
 */
class RatingMain extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile[]
     */
    public $uploadFiles;

    /**
     * @var int[]
     */
    public $deleteFiles;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rating_main}}';
    }

    /**
     * @return string
     */
    public static function getModule()
    {
        return 'rating';
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return 'rating-main';
    }

    /**
     * {@inheritDoc}
     * @return array
     */
    public function behaviors()
    {
        return [
            AuthorBehavior::class,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['id_tree', 'order_asc'], 'integer'],
            [['note', 'log_change'], 'string'],
            [['date_create'], 'safe'],
            [['name'], 'string', 'max' => 200],
            [['author'], 'string', 'max' => 250],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],
            [['id_tree'], 'exist', 'skipOnError' => true, 'targetClass' => Tree::class, 'targetAttribute' => ['id_tree' => 'id']],
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
            'id' => '#',
            'id_tree' => '# Tree',
            'name' => 'Наименование',
            'order_asc' => 'Сортировка по возрастанию',
            'note' => 'Описание',
            'log_change' => 'Журнал изменения',
            'date_create' => 'Дата создания',
            'author' => 'Автор',
            'uploadFiles' => 'Файлы',
            'deleteFiles' => 'Отметьте файлы для удаления',
        ];
    }

    /**
     * Файлы, прикрепленные к новости
     * @param $idFiles int[]
     * @return \yii\db\ActiveQuery
     */
    public function getFiles($idFiles = [])
    {
        $relation = $this->hasMany(File::class, ['id_model' => 'id'])
            ->where([
                'model' => $this->getModel(),
            ]);
        if (is_array($idFiles) && count($idFiles) > 0) {
            $relation->andWhere(['in', 'id', $idFiles]);
        }
        return $relation;
    }

    /**
     * @return array
     * @uses \app\models\rating\RatingMain
     */
    public function getCheckListBoxUploadFilesGallery()
    {
        return ArrayHelper::map($this->getFiles()->all(), 'id', 'file_name');
    }

    /**
     * Gets query for [[RatingDatas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRatingDatas()
    {
        return $this->hasMany(RatingData::class, ['id_rating_main' => 'id']);
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
     * Gets query for [[Tree]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTree()
    {
        return $this->hasOne(Tree::class, ['id' => 'id_tree']);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @param array $changedAttributes
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
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
     * @throws ErrorException
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function afterDelete()
    {
        // удаление файлов
        $this->deleteUploadFiles();

        // удаление каталога
        $this->deleteFolder();

        parent::afterDelete();
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
                $fileModel = new File();
                $fileModel->file_name = \Yii::$app->storage->addEndSlash($path) . $file->name;
                $fileModel->model = $this->getModel();
                $fileModel->id_model = $this->id;
                if ($fileModel->save()) {
                    //$this->link('files', $fileModel);
                }
            });
        }
    }

    /**
     * Каталог для загрузки файлов
     * @return string
     */
    protected function getPathFiles()
    {
        $path = \Yii::$app->params['news']['path']['files'];
        return $this->getPathWithReplace($path);
    }

    /**
     * Корневой каталог хранения файлов
     * @return string
     */
    protected function getPathRoot()
    {
        $path = \Yii::$app->params['news']['path']['root'];
        return $this->getPathWithReplace($path);
    }

    /**
     * Формирование пути для сохранения с подстановками (идентификатор, код организации)
     * @param string $path
     * @return string
     */
    protected function getPathWithReplace(string $path)
    {
        $path = str_replace('{id}', ($this->id ? $this->id : 'no_id'), $path);
        $path = str_replace('{code_no}', Yii::$app->userInfo->current_organization, $path);
        $path = str_replace('{module}', $this->getModel(), $path);
        return $path;
    }

    /**
     * Удаление каталога, перед удалением новости
     * @return
     * @throws ErrorException
     */
    public function deleteFolder()
    {
        $path = Yii::getAlias('@webroot') . $this->getPathWithReplace($this->getPathRoot());
        FileHelper::removeDirectory($path);
    }

    /**
     * Удаление файлов
     * @param int[] $idFiles
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @uses \app\modules\admin\controllers\NewsController
     * @uses \app\models\news\News::afterSave()
     */
    protected function deleteUploadFiles($idFiles = [])
    {
        // получение идентификаторов файлов
        $files = $this->getFiles($idFiles)->all();

        // удаление файлов с диска
        foreach ($files as $file) {
            $file->delete();
        }
    }


    public function getRatingData($year)
    {
        $sort = $this->order_asc ? SORT_ASC : SORT_DESC;
        return RatingData::find()
            ->where(['id_rating_main'=>$this->id, 'rating_year'=>$year])
            ->orderBy([
                'rating_year' => $sort,
                'rating_period' => $sort,
            ])
            ->all();
    }

    /**
     * Годы в рейтингах
     * @return array
     */
    public function getYears()
    {
        $query = new Query();
        return $query->from('{{%rating_data}}')
            ->where(['id_rating_main' => $this->id])
            ->orderBy(['rating_year' => SORT_ASC])
            ->select('rating_year')
            ->distinct(true)
            ->all();
    }

}
