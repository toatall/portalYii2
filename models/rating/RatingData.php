<?php

namespace app\models\rating;

use app\behaviors\AuthorBehavior;
use app\helpers\UploadHelper;
use app\models\File;
use Yii;
use app\models\User;
use yii\base\ErrorException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%rating_data}}".
 *
 * @property int $id
 * @property int $id_rating_main
 * @property string|null $note
 * @property int $rating_year
 * @property string $rating_period
 * @property string|null $log_change
 * @property string $date_create
 * @property string $author
 *
 * @property RatingMain $ratingMain
 * @property User $author0
 */
class RatingData extends \yii\db\ActiveRecord
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
     * Периоды для рейтинга
     * @var array
     */
    private $periods = [
        // месяцы
        '01_1_mes' => 'Январь',
        '02_1_mes' => 'Февраль',
        '03_1_mes' => 'Март',
        '04_1_mes' => 'Апрель',
        '05_1_mes' => 'Май',
        '06_1_mes' => 'Июнь',
        '07_1_mes' => 'Июль',
        '08_1_mes' => 'Август',
        '09_1_mes' => 'Сентябрь',
        '10_1_mes' => 'Октябрь',
        '11_1_mes' => 'Ноябрь',
        '12_1_mes' => 'Декабрь',
        // кварталы
        '03_2_kv' => '1 квартал',
        '06_2_kv' => '2 квартал',
        '09_2_kv' => '3 квартал',
        '12_2_kv' => '4 квартал',
        // полугодия
        '06_3_pol' => '1 полугодие',
        '12_3_pol' => '2 полугодие',
        // 9 месяцев
        '09_4_9mes' => '9 месяцев',
        // год
        '12_5_god' => 'Годовой',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%rating_data}}';
    }

    /**
     * @return string
     */
    public function getModel()
    {
        return 'rating-data';
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
            [['rating_year', 'rating_period'], 'required'],
            [['id_rating_main', 'rating_year'], 'integer'],
            [['note', 'log_change'], 'string'],
            [['date_create'], 'safe'],
            [['rating_period'], 'string', 'max' => 200],
            [['author'], 'string', 'max' => 250],
            [['id_rating_main'], 'exist', 'skipOnError' => true, 'targetClass' => RatingMain::class, 'targetAttribute' => ['id_rating_main' => 'id']],
            [['author'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['author' => 'username_windows']],
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
            'id_rating_main' => 'Id Rating Main',
            'note' => 'Примечание',
            'rating_year' => 'Год',
            'rating_period' => 'Период',
            'rating_name' => 'Период',
            'log_change' => 'Журнал изменений',
            'date_create' => 'Дата создания',
            'author' => 'Автор',
            'periodName' => 'Период',
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
     * @uses \app\modules\admin\controllers\RatingDataController::createAction()
     * @uses \app\modules\admin\controllers\RatingDataController::actionUpdate()
     */
    public function getCheckListBoxUploadFiles()
    {
        return ArrayHelper::map($this->getFiles()->all(), 'id', 'file_name');
    }

    /**
     * Gets query for [[RatingMain]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRatingMain()
    {
        return $this->hasOne(RatingMain::class, ['id' => 'id_rating_main']);
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
     * Доступные года для рейтига (+- 2 года от текущего)
     * @return array
     */
    public function getYears()
    {
        $y = date('Y');
        $resultYears = array();
        for ($i = ($y - 2); $i <= ($y + 2); $i++) {
            $resultYears[$i] = $i;
        }
        return $resultYears;
    }

    /**
     * Периоды
     * @return array
     */
    public function getPeriods()
    {
        return $this->periods;
    }

    /**
     * Наименование периода
     * @return string|null
     */
    public function getPeriodName()
    {
        return $this->periods[$this->rating_period] ?? null;
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
}
