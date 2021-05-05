<?php

namespace app\modules\events\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "{{%contest_arts}}".
 *
 * @property int $id
 * @property string $date_show
 * @property string $department_name
 * @property string|null $department_ad_group
 * @property string $image_original
 * @property string|null $image_original_author
 * @property string $image_reproduced
 * @property string|null $description_original
 * @property string|null $description_reproduced
 * @property string|null $qr_code_file
 * @property string|null $date_create
 * @property string|null $date_update
 * @property string|null $image_original_title
 * @property string|null $image_reproduced_title
 * @property string $date_show_2
 *
 * @property ContestArtsResults[] $contestArtsResults
 */
class ContestArts extends \yii\db\ActiveRecord
{
    /**
     * Изображение с оригиналом
     * @var UploadedFile
     */
    public $imageOriginal;
    
    /**
     * Удалить изображение с оригиналом
     * @var boolean
     */
    public $delImageOriginal;
    
    /**
     * Изображение с репродукцией
     * @var UploadedFile
     */    
    public $imageReproduced;
    
    /**
     * Удалить изображение с репродукцией
     * @var boolean
     */
    public $delImageReproduced;
    
    /**
     * Изображение QR кода
     * @var UploadedFile
     */
    public $imageQrCode;
    
    /**
     * Удалить изображение QR кода
     * @var boolean
     */
    public $delImageQrCode;
    
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%contest_arts}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['date_show', 'date_show_2', 'department_name', 'image_original_author',
                'image_original_title', 'image_reproduced_title'], 'required'],
            [['imageOriginal', 'imageReproduced'], 'required', 'on' => 'create'],
            [['date_show', 'date_create', 'date_update'], 'safe'],
            [['description_original', 'description_reproduced'], 'string'],
            [['department_name', 'department_ad_group', 'image_original_author'], 'string', 'max' => 300],
            [['image_original', 'image_reproduced', 'qr_code_file'], 'string', 'max' => 500],
            [['image_original_title', 'image_reproduced_title'], 'string', 'max' => 1000],
            [['imageOriginal', 'imageReproduced', 'imageQrCode'], 'file'],
            [['delImageOriginal', 'delImageReproduced', 'delImageQrCode'], 'boolean'],
        ];
    }
    

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '#',
            'date_show' => 'Дата показа с',
            'date_show_2' => 'Дата показа до',
            'department_name' => 'Отдел',
            'department_ad_group' => 'Группа отдела (наименование как в Active Directory)',
            'image_original' => 'Изображение оригинал',
            'image_original_author' => 'Автор оригинала',
            'image_reproduced' => 'Изображение репродукция',
            'description_original' => 'Описание оригинала',
            'description_reproduced' => 'Описание репродукции',
            'image_original_title' => 'Название оригинала',
            'image_reproduced_title' => 'Название репродукции',
            'qr_code_file' => 'Изображение QR кода',
            'date_create' => 'Дата создания',
            'date_update' => 'Дата изменения',            
            // дополнительные поля
            'imageOriginal' => 'Изображение оригинал',
            'delImageOriginal' => 'Удалить изображение оригинал',
            'imageReproduced' => 'Изображение репродукция',
            'delImageReproduced' => 'Удалить изображение репродукция',
            'imageQrCode' => 'Изображение QR кода',
            'delImageQrCode' => 'Удалить изображение QR кода'
        ];
    }   
    
    /**
     * {@inheritdoc}
     * @param bool $insert
     * @return boolean
     */
    public function beforeSave($insert) 
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        
        $this->uploadImage('imageOriginal', 'image_original', 'delImageOriginal');
        $this->uploadImage('imageReproduced', 'image_reproduced', 'delImageReproduced');
        $this->uploadImage('imageQrCode', 'qr_code_file', 'delImageQrCode');
        
        $this->date_show = new \yii\db\Expression("convert(date, '{$this->date_show}', 104)");
        $this->date_show_2 = new \yii\db\Expression("convert(date, '{$this->date_show_2}', 104)");
        
        if (!$insert) {
            $this->date_update = new \yii\db\Expression('getdate()');
        }
        return true;
    }
    
    /**
     * {@inheritdoc}
     */
    public function afterFind() 
    {
        parent::afterFind();
        $this->date_show = Yii::$app->formatter->asDate($this->date_show);
        $this->date_show_2 = Yii::$app->formatter->asDate($this->date_show_2);
    }
    
    /**
     * {@inheritdoc}
     */
    public function beforeDelete() 
    {
        if (!parent::beforeDelete()) {
            return false;
        }
        $this->deleteAllFiles();
        return true;
    }


    /**
     * Путь для сохранения файлов
     * @return string
     * @throws Exception
     */
    protected function getPath()
    {        
        if (!isset(Yii::$app->params['modules']['events']['contest-atrs']['images'])) {
            throw new Exception('Не задана настройка для сохранения пути. Файл params.php, параметр modules.events.contest-arts.images');
        }
        return Yii::$app->params['modules']['events']['contest-atrs']['images'];
    }
        
        
    /**
     * Загрузка изображения
     * @param string $fileField
     * @param string $dbField
     * @param string $delField
     * @throws Exception
     */
    private function uploadImage($fileField, $dbField, $delField)
    {        
        /* @var $storage \app\components\Storage */
        $storage = \Yii::$app->storage;        
        if ($this->$fileField != null && $this->$fileField instanceof \yii\web\UploadedFile) {
            // если уже загружена другая картинка, то удаленить старую
            if (!$this->isNewRecord && $this->$dbField) {              
                $storage->deleteFile($this->$dbField);
            }
            $fileName = $storage->saveUploadedFile($this->$fileField, $this->getPath(), true);            
            $this->$dbField = $this->getPath() . basename($fileName);
        }        
        // просто удалить файл
        else {            
            if ($this->$delField) {                                
                // удаление файла
                if ($storage->deleteFile($this->$dbField)) {
                    $this->$dbField = '';
                }
            }
        }
    }
    
    /**
     * Удаление файлов с диска
     */
    private function deleteAllFiles()
    {
        /* @var $storage \app\components\Storage */
        $storage = \Yii::$app->storage;
        
        if ($this->image_original) {
            $storage->deleteFile($this->image_original);
        }
        
        if ($this->image_reproduced) {
            $storage->deleteFile($this->image_reproduced);
        }
        
        if ($this->qr_code_file) {
            $storage->deleteFile($this->qr_code_file);        
        }
    }
       
    /**
     * Получение списка победителей
     * Выбираются только правильные ответы и суммируются
     * Выводятся по убыванию, кто больше всех ответил, тот сверху
     * Так как могут быть количество ответов одинаковое, то дополнительно добавлена
     * сортировка по дате по возрастанию, т.е. тот кто ответил раньше, то будет выше
     * @return array
     */
    public static function getWinners()
    {
        $query = "
            select
                 isnull(usr.fio, arts_res.author) fio
                ,arts_res.author
                ,count(arts_res.id) count_wins               
                ,sum(cast(format(arts_res.date_create, 'yyyyMMddHHmmss') as bigint)) timesum
            from {{%contest_arts}} arts
                left join {{%contest_arts_results}} arts_res on arts_res.id_arts = arts.id
                left join {{%user}} usr on arts_res.author = usr.username
            where arts_res.is_right=1
                and arts.date_show_2 <= cast(getdate() as date)
            group by isnull(usr.fio, arts_res.author), arts_res.author
            order by count_wins desc, timesum asc";
        return \Yii::$app->db->createCommand($query)->queryAll();
    }
    
    /**
     * Проверка разрешения возможности угадывания
     * 1. Если пользователь не из Управления, то нельзя голосовать
     * 2. Если время голосования еще не наступило или уже закончилось
     * 3. Если пользователь уже голосовал, то уже нельзя
     * 4. Если пользователь из того же отдела, которй выставил картину, то нельзя
     * @param integer $idModelArts
     */
    public function isAllow()
    {        
        // 1.
        if (!Yii::$app->user->identity->isOrg('8600')) {
            return 'В конкурсе участвуют только сотрудники Управления!';
        }
        
        // 2.       
        $hour = date('H');
        if ($this->date_show == Yii::$app->formatter->asDate('now') && $hour < 8) {
            return 'Время для ответа еще не наступило!';
        }
        if ($this->date_show_2 == Yii::$app->formatter->asDate('1 day') && $hour >= 16) {
            return 'Время для ответа уже закончилось!';
        }        
        
        // 3.
        $query = (new \yii\db\Query())
            ->from('{{%contest_arts_results}}')
            ->where([
                'author' => Yii::$app->user->identity->username,
                'id_arts' => $this->id,
            ])
            ->one();
        if ($query != null) {
            return "Вы уже отвечали " . Yii::$app->formatter->asDatetime($query['date_create']);
        }
        // 4.
        $userGroups = [];        
        if (!empty(Yii::$app->user->identity->memberof)) {
            $userGroups = explode(', ', Yii::$app->user->identity->memberof);
        }        
        if (!$userGroups) {
            return 'Не удалось определи ваши группы в Active Directory!';
        }
        
        $query = (new \yii\db\Query())
            ->from('{{%contest_arts}}')
            ->where(['id' => $this->id])
            ->andWhere(['in', 'department_ad_group', $userGroups])
            ->exists();
        if ($query) {
            return 'Вы не можете голосовать за картину Вашего отдела!';
        }
        
        return null;
    }
    
     /**
     * Проверка разрешения возможности голосования по разным номинациям
     * 1. Если пользователь не из Управления, то нельзя голосовать     
     * 2. Если пользователь из того же отдела, которй выставил картину, то нельзя
     * 3. Если пользователь уже голосовал
     * @param integer $idModelArts
     */
    public function isAllowVote()
    {
        // 1.
        if (!Yii::$app->user->identity->isOrg('8600')) {
            return 'В конкурсе участвуют только сотрудники Управления!';
        }
        
        // 2.
        $userGroups = [];        
        if (!empty(Yii::$app->user->identity->memberof)) {
            $userGroups = explode(', ', Yii::$app->user->identity->memberof);
        }        
        if (!$userGroups) {
            return 'Не удалось определи ваши группы в Active Directory!';
        }
        
        $query = (new \yii\db\Query())
            ->from('{{%contest_arts}}')
            ->where(['id' => $this->id])
            ->andWhere(['in', 'department_ad_group', $userGroups])
            ->exists();
        if ($query) {
            return 'Вы не можете голосовать за картину Вашего отдела!';
        }
        
        // 3.
        $query = (new \yii\db\Query())
            ->from('{{%contest_arts_vote}}')
            ->where([
                'id_contest_arts' => $this->id,
                'author' => Yii::$app->user->identity->username,
            ])
            ->exists();
        if ($query) {
            return 'Вы уже проголосовали';
        }
        
        return null;
    }


    /**
     * Количество ответов, которые еше не помеченные правильные они или нет
     * @return int
     */
    public function countNotSetRight()
    {
        return (new \yii\db\Query())
            ->from('{{%contest_arts_results}}')
            ->where([
                'is_right' => null,
                'id_arts' => $this->id,
            ])
            ->count();
    }
    
    /**
     * Все ответы по текущей картине
     * @return array|null
     */
    public function getAnswers()
    {
        return (new \yii\db\Query())
            ->select('t.*, us.fio, us.department')
            ->from('{{%contest_arts_results}} t')
            ->leftJoin('{{%user}} us on t.author = us.username_windows')
            ->where(['t.id_arts' => $this->id])                
            ->all();
    }
    
    /**
     * @return array
     */
    public function getStatistic()
    {
        return (new \yii\db\Query())
            ->select('count(id) count_all, sum(case when is_right = 1 then 1 else 0 end) count_right')
            ->from('{{%contest_arts_results}}')
            ->where(['id_arts' => $this->id])
            ->one();
    }
    
    public function getDateEnsStr()
    {
        $d = new \DateTimeImmutable($this->date_show_2);
        return Yii::$app->formatter->asDate($d->modify('-1 day')) . ' 16:00';
    }
    
    
}
