<?php

namespace app\models\news;

use app\components\Storage;
use app\models\Image;
use Yii;
use app\models\Tree;
use app\models\Organization;
use app\models\User;
use app\helpers\DateHelper;
use yii\db\Exception;
use yii\db\Expression;
use yii\db\StaleObjectException;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
use app\models\File;
use yii\helpers\ArrayHelper;
use app\helpers\UploadHelper;
use yii\db\Query;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property int $id
 * @property int $id_tree
 * @property string $id_organization
 * @property string $title
 * @property string $message1
 * @property string $message2
 * @property string $author
 * @property int $general_page
 * @property string|null $date_start_pub
 * @property string|null $date_end_pub
 * @property int $flag_enable
 * @property string|null $thumbail_title
 * @property string|null $thumbail_image
 * @property string|null $thumbail_text
 * @property string $date_create
 * @property string $date_edit
 * @property string|null $date_delete
 * @property string|null $log_change
 * @property int|null $on_general_page
 * @property int|null $count_like
 * @property int|null $count_comment
 * @property int|null $count_visit
 * @property string|null $tags
 * @property string $date_sort
 * @property string $date_top
 * @property string $from_department
 *
 * @property Tree $tree
 * @property Organization $organization
 * @property User $modelAuthor
 * @property NewsComment[] $newsComments
 * @property array $newsLikes
 * @property File[] $files
 *
 * @property boolean $liked
 *
 */
class News extends \yii\db\ActiveRecord
{
    /**
     * Указание отдела для пользователей,
     * которые размещают новости на главной и с Управления
     */
    const SCENARIO_DEPARTMENT_REQUIRED = 'department_required';

    /**
     * Дата закрепления
     * @var string
     */
    public $date_top;

    /**
     * Миниатюра
     * @var UploadedFile
     */
    public $uploadThumbnailImage;

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
     * Изображения (галерея)
     * @var UploadedFile[]
     */
    public $uploadImages;

    /**
     * Изображения отмеченные для удаления
     * @var int[]
     */
    public $deleteImages;

    /**
     * Удаление миниатюры
     * @var boolean
     */
    public $deleteThumbnailImage;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%news}}';
    }

    /**
     * Используемый модуль
     * @return string
     */
    public static function getModule()
    {
        return 'news';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'message2', 'date_start_pub', 'date_end_pub'], 'required'],
            [['id_tree', 'general_page', 'flag_enable', 'on_general_page', 'count_like', 'count_comment', 'count_visit'], 'integer'],
            [['message1', 'message2', 'log_change'], 'string'],
            [['date_start_pub', 'date_end_pub', 'date_create', 'date_edit', 'date_delete', 'date_sort', 'date_top'], 'safe'],
            [['id_organization'], 'string', 'max' => 5],
            [['title', 'from_department'], 'string', 'max' => 500],
            [['author'], 'string', 'max' => 250],
            [['thumbail_title', 'thumbail_image', 'thumbail_text'], 'string', 'max' => 255],
            [['tags'], 'string', 'max' => 1000],
            [['id_tree'], 'exist', 'skipOnError' => true, 'targetClass' => Tree::class, 'targetAttribute' => ['id_tree' => 'id']],
            [['id_organization'], 'exist', 'skipOnError' => true, 'targetClass' => Organization::class, 'targetAttribute' => ['id_organization' => 'code']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],
            [['uploadThumbnailImage'], 'file', 'skipOnEmpty' => true],
            [['deleteThumbnailImage'], 'boolean'],
            [['uploadFiles', 'uploadImages'], 'file', 'skipOnEmpty' => true, 'maxFiles' => 30],
            [['deleteFiles', 'deleteImages'], 'safe'],
            [['from_department'], 'required', 'on' => self::SCENARIO_DEPARTMENT_REQUIRED],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'id_tree' => 'Раздел',
            'id_organization' => 'Организация',
            'title' => 'Заголовок',
            'message1' => 'Кратко',
            'message2' => 'Основной текст',
            'author' => 'Автор',
            'general_page' => 'Главная страница',
            'date_start_pub' => 'Начало публикации',
            'date_end_pub' => 'Окончание публикации',
            'humbnail Imageflag_enable' => 'Опубликовать',
            'thumbail_title' => 'Thumbail Title',
            'thumbail_image' => 'Миниатюра',
            'thumbail_text' => 'Thumbail Text',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
            'log_change' => 'История изменений',
            'on_general_page' => 'Новость дня',
            'count_like' => 'Количество лайков',
            'count_comment' => 'Количество комментариев',
            'count_visit' => 'Количество просмотров',
            'tags' => 'Теги',
            'date_sort' => 'Дата',
            'date_top' => 'Закрепить новость до',
            'files' => 'Файлы',
            'images' => 'Изображения',
            'deleteThumbnailImage' => 'Удалить миниатюру',
            'flag_enable' => 'Опубликовано',
            'uploadFiles' => 'Файлы',
            'deleteFiles' => 'Отметьте файлы для удаления',
            'uploadImages' => 'Изображения',
            'deleteImages' => 'Отментьте изображения для удаления',
            'from_department' => 'Отдел (автор материала)',
        ];
    }

    /**
     * {@inheritDoc}
     * @param mixed $condition
     * @return array|\yii\db\ActiveRecord|\yii\db\ActiveRecordInterface|null
     */
    public static function findOne($pk)
    {
        $query = static::find()->where(['id' => $pk]);
        if (!Yii::$app->user->can('admin')) {
            $query->andWhere(['date_delete' => null]);
        }
        return $query->one();
    }

    /**
     * Поиск новости frontend
     * @param $pk
     * @return News|array|null
     */
    public static function publicFindOne($pk)
    {
        return static::find()
            ->where([
                'id' => $pk,
                'flag_enable' => true,
                'date_delete' => null,
            ])
            ->andFilterWhere(['<', 'date_start_pub', (new Expression('getdate()'))])
            ->andFilterWhere(['>', 'date_end_pub', (new Expression('getdate()'))])
            ->one();
    }

    /**
     * Gets query for [[Tree]].
     *
     * @return \yii\db\ActiveQuery|TreeQuery
     */
    public function getTree()
    {
        return $this->hasOne(Tree::class, ['id' => 'id_tree']);
    }

    /**
     * Gets query for [[Organization]].
     *
     * @return \yii\db\ActiveQuery|OrganizationQuery
     */
    public function getOrganization()
    {
        return $this->hasOne(Organization::class, ['code' => 'id_organization']);
    }

    /**
     * Gets query for [[Author0]].
     *
     * @return \yii\db\ActiveQuery|UserQuery
     */
    public function getModelAuthor()
    {
        return $this->hasOne(User::class, ['username_windows' => 'author']);
    }

    /**
     * Gets query for [[NewsComments]].
     *
     * @return \yii\db\ActiveQuery|NewsCommentQuery
     */
    public function getNewsComments()
    {
        return $this->hasMany(NewsComment::class, ['id_news' => 'id']);
    }

    /**
     * @return \yii\db\Query
     */
    public function getHistory()
    {        
        return (new Query())
            ->select(['u.fio', 'detail.author', 'u.current_organization', 'u.organization_name', 'u.department', 
                'u.user_disabled_ad', 'detail.host', 'detail.ip', 'detail.date_create'])
            ->from('{{%history}} t')
            ->leftJoin('{{%history_detail}} detail', 'detail.id_history = t.id')
            ->leftJoin('{{%user}} u', 'u.username = detail.author')
            ->where([
                'url' => Url::to(['/news/view', 'id'=>$this->id]),
            ]);
    }

    /**
     * @return \yii\db\Query
     */
    public function getLikes()
    {
        return (new Query())
            ->select(['u.fio', 't.username', 'u.current_organization', 'u.organization_name', 'u.department', 
                'u.user_disabled_ad', 't.ip_address', 't.date_create'])
            ->from('{{%news_like}} t')
            ->leftJoin('{{%user}} u', 'u.username = t.username')
            ->where([
                't.id_news' => $this->id,
            ]);
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
                'model' => self::getModule(),//static::getModule(),
            ]);
        if (is_array($idFiles) && count($idFiles) > 0) {
            $relation->andWhere(['in', 'id', $idFiles]);
        }
        return $relation;
    }

    /**
     * Изображения, прикрепленные к новости
     * @param $idImages int[]
     * @return \yii\db\ActiveQuery
     */
    public function getImages($idImages = [])
    {
        $relation = $this->hasMany(Image::class, ['id_model' => 'id'])
            ->where([
                'model' => self::getModule(), //static::getModule(),
            ]);
        if (is_array($idImages) && count($idImages) > 0) {
            $relation->andWhere(['in', 'id', $idImages]);
        }
        return $relation;
    }

    /**
     * Вывод списка файлов для CheckBoxList
     * @return array
     * @uses \app\modules\admin\controllers\NewsController::actionCreate()
     * @uses \app\modules\admin\controllers\NewsController::actionUpdate()
     */
    public function getCheckListBoxUploadFilesGallery()
    {
        return ArrayHelper::map($this->getFiles()->all(), 'id', 'file_name');
    }

    /**
     * Вывод списка изображений для CheckBoxList
     * @return array
     * @uses \app\modules\admin\controllers\NewsController::actionCreate()
     * @uses \app\modules\admin\controllers\NewsController::actionUpdate()
     */
    public function getCheckListBoxUploadImagesGallery()
    {
        return ArrayHelper::map($this->getImages()->all(), 'id', 'image_name');
    }

    /**
     * {@inheritdoc}
     * @return NewsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new NewsQuery(get_called_class());
    }

    /**
     * Сокращенное наименование новости
     * @return string
     */
    public function getTitleShort()
    {
        $len = 70;
        $result = iconv_substr($this->title, 0, $len);
        if (iconv_strlen($this->title) > $len) {
            $result .= '...';
        }
        return $result;
    }

    /**
     * {@inheritDoc}
     * @throws \yii\base\InvalidConfigException
     */
    public function afterFind()
    {
        if (!DateHelper::equalsDates($this->date_create, $this->date_sort)) {
            $this->date_top = Yii::$app->formatter->asDate($this->date_sort);
        }
        if ($this->date_start_pub) {
            $this->date_start_pub = Yii::$app->formatter->asDate($this->date_start_pub);
        }
        if ($this->date_end_pub) {
            $this->date_end_pub = Yii::$app->formatter->asDate($this->date_end_pub);
        }
        parent::afterFind();
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @return bool
     * @throws \yii\base\InvalidConfigException
     */
    public function beforeSave($insert)
    {
        // author
        if ($this->isNewRecord) {
            $this->author = Yii::$app->user->identity->username;
        }
        // date_update
        else {
            $this->date_edit = new Expression('getdate()');
        }

        // date_sort
        if ($this->date_top != null) {
            $this->date_sort = DateHelper::asDateWithTime($this->date_top);
        }
        else {
            if ($this->isNewRecord) {
                $this->date_sort = new Expression('getdate()');
            }
            else {
                $this->date_sort = Yii::$app->formatter->asDatetime($this->date_create);
            }
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritDoc}
     * @param bool $insert
     * @param array $changedAttributes
     * @throws StaleObjectException
     * @throws \Throwable
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        // удаление выбранных файлов
        if (is_array($this->deleteFiles) && count($this->deleteFiles) > 0) {
            $this->deleteUploadFiles($this->deleteFiles);
        }

        // удаление выбранных изображений
        if (is_array($this->deleteImages) && count($this->deleteImages) > 0) {
            $this->deleteUploadImage($this->deleteImages);
        }

        // Загрузка файлов
        $this->uploadFiles();

        // Загрузка изображений
        // Для каждого изображения создается дополнительное изображение (миниатюра)
        $this->uploadImagesGallery();

        // Загрузка и сохранение миниатюры
        $this->uploadThumbnail();
    }

    /**
     * @throws StaleObjectException
     * @throws \Throwable
     * @throws \yii\base\ErrorException
     */
    public function afterDelete()
    {
        // удаление файлов
        $this->deleteUploadFiles();

        // удаление изображений
        $this->deleteUploadImage();

        // удаление миниатюры
        $this->deleteThumbFile();

        // удаление каталога
        $this->deleteFolder();

        parent::afterDelete();
    }

    /**
     * Удаление миниатюры
     * @return bool
     */
    protected function deleteThumbFile()
    {
        if ($this->thumbail_image != null) {
            return \Yii::$app->storage->deleteFile($this->thumbail_image);
        }
    }


    /**
     * Удаление файлов
     * @param int[] $idFiles
     * @throws \Throwable
     * @throws StaleObjectException
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

    /**
     * Удаление изображений
     * @param int[] $idFiles
     * @throws \Throwable
     * @throws StaleObjectException
     * @uses \app\modules\admin\controllers\NewsController
     * @uses \app\models\news\News::afterSave()
     */
    protected function deleteUploadImage($idFiles = [])
    {
        // получение идентификаторов файлов
        $images = $this->getImages($idFiles)->all();

        // удаление файлов с диска
        foreach ($images as $image) {
            $image->delete();
        }
    }

    /**
     * Удаление каталога, перед удалением новости
     * @return
     * @throws \yii\base\ErrorException
     */
    public function deleteFolder()
    {
        $path = Yii::getAlias('@webroot') . $this->getPathWithReplace($this->getPathRoot());
        FileHelper::removeDirectory($path);
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
                $fileModel->model = self::getModule(); //News::getModule();// static::getModule();
                $fileModel->id_model = $this->id;
                if ($fileModel->save()) {
                    //$this->link('files', $fileModel);
                }
            });
        }
    }

    /**
     * Загрузка изображений
     */
    private function uploadImagesGallery()
    {
        if (is_array($this->uploadImages) && count($this->uploadImages) > 0) {
            $helper = new UploadHelper($this->getPathImages());
            $helper->uploadFiles($this->uploadImages, function ($file, $saveFile, $path) {

                /* @var $storage Storage */
                $storage = Yii::$app->storage;

                $prefix = Yii::$app->params['news']['thumbnailPrefix'];

                // сохранение изображения в БД
                $fileModel = new Image();
                $fileModel->image_name = $storage->addEndSlash($path) . $file->name;
                $fileModel->image_name_thumbs = $storage->addFileNamePrefix($fileModel->image_name, $prefix);
                $fileModel->model = self::getModule();// News::getModule(); //static::getModule();
                $fileModel->id_model = $this->id;
                if ($fileModel->save()) {
                    //$this->link('images', $fileModel);
                }

                // создание миниатюр
                $this->createThumbnail($saveFile);

                // если размер превышает установленный лимит, то уменьшается размер изображения
                \Yii::$app->storage->resizeImage($saveFile, \Yii::$app->params['news']['size']['imageMaxWidth'], \Yii::$app->params['news']['size']['imageMaxHeight']);
            });
        }
    }

    /**
     * Загрузка файла-миниатюры
     * @throws Exception
     */
    private function uploadThumbnail()
    {
        if ($this->uploadThumbnailImage && $this->uploadThumbnailImage instanceof \yii\web\UploadedFile) {
            // удаление старой миниатюры
            if (!$this->isNewRecord && $this->thumbail_image != null) {
                // если имя такое же, то оставляем, просто заменим,
                // т.к. при удалении и загрузке нового изображения - удаляется новое (видимо операция удаления медленнее, чем загрузки)
                if (basename($this->thumbail_image) != basename($this->uploadThumbnailImage->name)) {
                    \Yii::$app->storage->deleteFile($this->thumbail_image);
                }
            }

            // загрузка новой миниатюры
            $path = $this->getPathThumb();
            $thumb = \Yii::$app->storage->saveUploadedFile($this->uploadThumbnailImage, $path);
            if ($thumb) {
                // изменение размеров
                \Yii::$app->storage->resizeImage($thumb, \Yii::$app->params['news']['size']['thumbnailMaxWidth'], \Yii::$app->params['news']['size']['thumbnailMaxHeight']);
                $this->updateThumb(\Yii::$app->storage->addEndSlash($path) . $this->uploadThumbnailImage->name);
            }
        }
        // удаление миниатюры без сохранения новой
        else
        {
            if ($this->deleteThumbnailImage) {
                // удаление файла
                \Yii::$app->storage->deleteFile($this->thumbail_image);
                // сохранение в БД
                $this->updateThumb(null);
            }
        }
    }

    /**
     * Сохранение имени файла миниатюры в БД
     * @param string $thumb
     * @throws Exception
     */
    private function updateThumb($thumb)
    {
        if ($this->id) {
            \Yii::$app->db->createCommand()
                ->update($this::tableName(), [
                    'thumbail_image' => $thumb,
                ],[
                    'id' => $this->id,
                ])->execute();
            $this->thumbail_image = $thumb;
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
     * Каталог для загрузки изображений
     * @return string
     */
    protected function getPathImages()
    {
        $path = \Yii::$app->params['news']['path']['images'];
        return $this->getPathWithReplace($path);
    }

    /**
     * Каталог для загрузки миниатюр
     * @return string
     */
    protected function getPathThumb()
    {
        $path = \Yii::$app->params['news']['path']['thumbnail'];
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
     * Из загружаемых изображений (в галерею) создание миниатюр,
     * для предпросмотра галереи изображений
     * @param $image
     * @return mixed
     */
    private function createThumbnail($image)
    {
        $storage = \Yii::$app->storage;
        $thumbWidth = \Yii::$app->params['news']['size']['thumbnailMaxWidth'];
        $thumbHeight = \Yii::$app->params['news']['size']['thumbnailMaxHeight'];
        $prefix = Yii::$app->params['news']['thumbnailPrefix'];
        $thumbName = $storage->addFileNamePrefix($image, $prefix);
        return $storage->resizeImage($image, $thumbWidth, $thumbHeight, $thumbName);
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
        $path = str_replace('{module}', self::getModule(), $path);
        return $path;
    }

    /**
     * Миниатюра
     * @return string
     */
    public function getThumbnail()
    {
        $thumb = Yii::getAlias('@webroot') . $this->thumbail_image;
        if (is_file($thumb) && file_exists($thumb)) {
            return $this->thumbail_image;
        }
        return $this->getDefaultThumbnail();
    }

    /**
     * Миниатюра по умолчанию (если не установлена миниатюра)
     * @return string
     */
    protected function getDefaultThumbnail()
    {
        return Yii::$app->params['news']['defaultThumb'];
    }

    /**
     * Лайкал текущий пользователь или нет
     * @return bool
     */
    public function getLiked()
    {
        if (Yii::$app->user->isGuest) {
            return false;
        }
        return (new \yii\db\Query())
            ->from('{{%news_like}}')
            ->where([
                'id_news' => $this->id,
                'username' => \Yii::$app->user->identity->username,
            ])
            ->exists();
    }

    /**
     * Лайк/дизлайк текущей новости
     * @return int
     * @throws Exception
     */
    public function like()
    {
        if ($this->liked) {
            \Yii::$app->db->createCommand()
                ->delete('{{%news_like}}',
                    [
                        'id_news' => $this->id,
                        'username' => \Yii::$app->user->identity->username,
                    ])
                ->execute();
        }
        else
        {
            \Yii::$app->db->createCommand()
                ->insert('{{%news_like}}', [
                    'id_news' => $this->id,
                    'username' => \Yii::$app->user->identity->username,
                    'ip_address' => $_SERVER['REMOTE_ADDR'],
                ])->execute();
        }
        $this->refresh();

        return $this->count_like;
    }

}
