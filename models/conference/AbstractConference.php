<?php

namespace app\models\conference;

use Yii;
use yii\db\Query;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use app\models\Tree;
use app\models\Access;
use yii\web\NotFoundHttpException;

/**
 * This is the model class for table "{{%conference}}".
 *
 * @property int $id
 * @property int $type_conference
 * @property string $theme
 * @property string|null $responsible
 * @property string|null $members_people
 * @property string|null $members_organization
 * @property string $date_start
 * @property int $time_start_msk
 * @property string|null $duration
 * @property int $is_confidential
 * @property string|null $place
 * @property string|null $note
 * @property string $date_create
 * @property string $date_edit
 * @property string|null $date_delete
 * @property string|null $log_change
 * @property string $status
 * @property string $editor
 * @property string $denied_text
 * @property string $approve_author
 * @property string $author
 */
abstract class AbstractConference extends \yii\db\ActiveRecord
{
    /**
    * ВКС с УФНС
    */
    const TYPE_VKS_UFNS = 1;
    const COLOR_VKS_UFNS = 'darkgreen'; // DarkGreen
        
    /**
     * ВКС с ФНС
     */
    const TYPE_VKS_FNS = 2;
    const COLOR_VKS_FNS = '#9400d3'; // DarkViolet
    
    /**
     * Собрания
     */
    const TYPE_CONFERENCE = 3;
    const COLOR_CONFERENCE = '#add8e6'; // LightBlue
    
    /**
     * Внешнее мероприятие
     */
    const TYPE_VKS_EXTERNAL = 4;
    const COLOR_VKS_EXTERNAL = '#ff8c00'; // DarkOrange

    // статусы
    const STATUS_COMPLETE = 'complete';
    const STATUS_APPROVE = 'approve';
    const STATUS_DENIED = 'denied';
    
    
    /**
     * Дата начала
     * @var string
     */
    public $onlyDateStart;

    /**
     * Время начала
     * @var string
     */
    public $onlyTimeStrat;
    
    /**
     * Место проведения
     * @var array
     */
    public $arrPlace;    


    /**
     * Тип конференции
     * @return mixed
     */
    abstract public static function getType();
    
    abstract public static function getTypeLabel();

    abstract public static function getModule();

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%conference}}';
    }

    /**
     * @var array
     */
    protected static $types = [
        'conference' => 'Собрания',
        'vksUfns' => 'ВКС с УФНС',
        'vksFns' => 'ВКС с ФНС',
        'vksExternal' => 'ВКС внешние',
    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type_conference', 'theme', 'date_start'], 'required'],
            [['type_conference', 'time_start_msk', 'is_confidential'], 'integer'],
            [['responsible', 'members_people', 'members_organization', 'note', 'log_change', 'denied_text'], 'string'],
            [['date_start', 'date_create', 'date_edit', 'date_delete'], 'safe'],
            [['theme', 'person_head', 'link_event', 'full_name_support_ufns'], 'string', 'max' => 500],
            [['duration'], 'string', 'max' => 20],
            [['place', 'date_test_vks', 'date_end'], 'safe'],
            [['format_holding'], 'string', 'max' => 50],
            [['members_count', 'members_count_ufns', 'count_notebooks'], 'string', 'max' => 10],
            [['material_translation', 'platform', 'editor', 'approve_author', 'author'], 'string', 'max' => 250],
            [['is_connect_vks_fns', 'is_change_time_gymnastic'], 'boolean'],
            [['arrPlace'], 'required'], 
            [['status'], 'string', 'max' => 15], 
            [['date_start'], function($attribute) {
                $query = (new Query())
                    ->from('{{%conference}}')
                    ->where([
                        'date_start' => $this->date_start,
                        'place' => $this->place,
                    ])
                    ->exists();
                if ($query) {
                    $this->addError($attribute, 'В данное время и в этом кабинете уже запланированно другое мероприятие');
                }                
            }, 'on' => 'request'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ИД',
            'type_conference' => 'Тип конференции',
            'theme' => 'Тема',
            'responsible' => 'Отвественные',
            'members_people' => 'Участники (сотрудники Управления)',
            'members_organization' => 'Участники (Инспекции)',
            'date_start' => 'Дата и время начала',
            'time_start' => 'Время начала',
            '_tempDateStart' => 'Дата начала',
            '_tempTimeStart' => 'Время начала',
            'time_start_msk' => 'Время московское',
            'duration' => 'Продолжительность',
            'is_confidential' => 'Конфиценциально',
            'place' => 'Место проведения',
            'arrPlace' => 'Место проведения',
            'note' => 'Примечание',
            'date_create' => 'Дата создания',
            'date_edit' => 'Дата изменения',
            'date_delete' => 'Дата удаления',
            'log_change' => 'Журнал изменений',
            
            'format_holding' => 'Формат проведения',
            'members_count' => 'Количество участников (планируемое)',
            'material_translation' => 'Материалы для трансляции',
            'members_count_ufns' => 'Количество участников со стороны Управления',
            'person_head' => 'Председательствующий (руководитель, заместитель)',
            'link_event' => 'Ссылка на мероприятие',
            'is_connect_vks_fns' => 'Подключение к ВКС ЦА ФНС России',
            'platform' => 'Платформа',
            'full_name_support_ufns' => 'ФИО тех. специалиста Управления',
            'date_test_vks' => 'Дата проведения тестового ВКС',
            'count_notebooks' => 'Количество ноутбуков',
            'is_change_time_gymnastic' => 'Требуется перенос проведения зарядки (требуется согласование с приемной)',
            'denied_text' => 'Причина отказа',
            'approve_author' => 'Автор согласования/отказа',
         ];
    }

    /**
     * @return string
     * @throws \yii\base\InvalidConfigException
     */
    public function getTime_start()
    {
        return Yii::$app->formatter->asTime($this->date_start, 'short');
    }

    /**
     * @return mixed
     */
    public function getParamNotifyEmail()
    {
        return Yii::$app->params['conference']['notifyMailAddress'];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public static function find()
    {
        return parent::find()
            ->where(['type_conference' => static::getType()])
            ->orderBy('date_start desc');
    }

    /**
     * Поиск событий на сегодня
     * @return \yii\db\ActiveQuery
     */
    public static function findToday()
    {
        return static::find()
            ->andWhere(['date_delete' => null])
            ->andWhere('convert(varchar, date_start, 104) = convert(varchar, getdate(), 104)')
            ->andWhere('(status is null or status = :status)', [
                ':status' => self::STATUS_COMPLETE,
            ]);
    }

    /**
     * Поиск frontend
     * @return \yii\db\ActiveQuery
     */
    public static function findPublic()
    {
        return parent::find()->where(['date_delete' => null]);
    }

    /**
     * {@inheritDoc}
     * @throws \yii\base\InvalidConfigException
     */
    public function afterFind()
    {
        parent::afterFind();
        if ($this->date_start) {
            $this->date_start = Yii::$app->formatter->asDatetime($this->date_start, 'dd.MM.yyyy HH:i');
        }
        if ($this->date_test_vks) {
            $this->date_test_vks = Yii::$app->formatter->asDatetime($this->date_test_vks, 'dd.MM.yyyy HH:i');
        }
        $this->arrPlace = explode(', ', $this->place);
    }

    /**
     * {@inheritDoc}
     * @return bool
     */
    public function beforeValidate()
    {
        $this->type_conference = static::getType();
        return parent::beforeValidate();
    }
    
    /**
     * {@inheritdoc}
     */
    public function beforeSave($insert) 
    {
        $this->editor = Yii::$app->user->identity->username;
        $this->place = is_array($this->arrPlace) ? implode(', ', $this->arrPlace) : $this->arrPlace;
        if (!empty($this->duration)) {
            $duration = explode(':', $this->duration);
            if (count($duration) > 1) {
                $this->date_end = Yii::$app->formatter->asDatetime(strtotime($this->date_start) + (intval($duration[0]) * 60 * 60) + (intval($duration[1]) * 60));
            }
        }
        if ($insert) {
            $this->saveStatusFirst();
            $this->author = Yii::$app->user->identity->username;
        }
        return parent::beforeSave($insert);
    }    
    
    /**
     * Сохранение статуса первоначального
     * Если пользователь с ролью conferenceManager,
     * то сразу согласованная заявка
     */
    private function saveStatusFirst()
    {
        if ($this->isNewRecord) {
            if (Yii::$app->user->can('permConferenceApprove')) {
                $this->status = self::STATUS_COMPLETE;
            }
            else {
                $this->status = self::STATUS_APPROVE;
            }
        }
    }

    /**
     * Вывод событий на сегодня (на главную страницу)
     * @return array
     */
    public static function eventsToday()
    {
        $queryResult = [
            'conference' => Conference::findToday()->all(),
            'vksUfns' => VksUfns::findToday()->all(),
            'vksFns' => VksFns::findToday()->all(),
            'vksExternal' => VksExternal::findToday()->all(),
        ];

        return Yii::$app->view->renderFile('@app/views/conference/today.php', [
            'queryResult' => $queryResult,
        ]);
    }

    /**
     * Описание типа
     * @param $type
     * @return mixed
     */
    public static function getLabelType($type)
    {
        return self::$types[$type] ?? $type;
    }
    
    /**
     * Типы мероприятий
     * @return array
     */
    public static function getTypes()
    {               
        return [
            self::TYPE_VKS_UFNS => 'ВКС с УФНС',
            self::TYPE_VKS_FNS => 'ВКС с ФНС',
            self::TYPE_CONFERENCE => 'Собрания',
            self::TYPE_VKS_EXTERNAL => 'ВКС внешние',
                
        ];
    }

    /**
     * @return bool
     * @throws \Exception
     */
    public function isFinished()
    {
        $dateNow = new \DateTime('now');
        $dateStart = new \DateTime($this->date_start);
        return ($dateNow > $dateStart);
    }

    /**
     * Отправка уведомления по почте
     * о создании/изменении мероприятия
     * @param string $to адрес получателя
     * @throws \yii\base\InvalidConfigException
     * @uses \app\modules\admin\controllers\VksUfnsController::actionUpdate()
     * @uses \app\modules\admin\controllers\VksUfnsController::createAction()
     */
    public function notifyEmail($typeLabel, $to=null)
    {
        // отправка только по утвержденным ВКС
        if ($this->status != self::STATUS_COMPLETE) {
            return;
        }

        if (empty($to)) {
            $to = $this->getParamEmailAddress();
        }

        $subject = 'Уведомление о ' . $typeLabel . ', назначенное на ' . Yii::$app->formatter->asDatetime($this->date_start);
        $message = '<h1>' . Yii::$app->formatter->asDate($this->date_start) . ' в ' .  Yii::$app->formatter->asTime($this->date_start) . ' будет проводиться ' . $typeLabel . '</h1>';
        $message .= '<br />Тема: ' . $this->theme;
        $message .= '<br />Участники ИФНС: ' . $this->members_organization;
        $message .= '<br /><br /><a href="' . Url::toRoute(['/conference/view', ['id'=>$this->id]], true) . '" target="_blank">Подробнее...</a>';

        Yii::$app->mailer->compose()
            ->setFrom('portal86@regions.tax.nalog.ru')
            ->setTo($to)
            ->setSubject($subject)
            ->setHtmlBody($message)
            ->setCharset('utf-8')
            ->send();
    }

    /**
     * @return string
     */
    private function getParamEmailAddress()
    {
        return Yii::$app->params['conference']['notifyMailAddress'];
    }

    /**
     * Место проведения мероприятия
     * @return array
     */
    public function dropDownListLocation()
    {
        $query = (new Query())
            ->from('{{%conference_location}}')
            ->all();
        return ArrayHelper::map($query, 'val', 'val');
    }    
    
    /**
     * Список для поля "Формат проведения"
     * @return array
     */
    public function dropDownListFormat()
    {
        return [
            'вебинар' => 'вебинар',
            'видеоконференция' => 'видеоконференция',
        ];
    }
    
    /**
     * Список для поля "Материалы для трансляции"
     * @return array
     */
    public function dropDownListMaterials()
    {
        return [
            'нет' => 'нет',
            'видео' => 'видео',
            'презентация' => 'презентация',
        ];
    }      
    
    /**
     * Цвет ярлычка для каждого вида мероприятия
     * @return string
     */
    public function getEventColor()
    {
        $color = '';
        switch ($this->type_conference) {
            case self::TYPE_VKS_UFNS: 
                $color = self::COLOR_VKS_UFNS;
                break;
            case self::TYPE_VKS_FNS:
                $color = self::COLOR_VKS_FNS;
                break;
            case self::TYPE_CONFERENCE:
                $color = self::COLOR_CONFERENCE;
                break;
            case self::TYPE_VKS_EXTERNAL:
                $color = self::COLOR_VKS_EXTERNAL;
                break;
        }
        return $color;
    }

    /**
     * Описание текущего типа
     * @return string
     */
    public function typeLabel()
    {
        switch ($this->type_conference) {
            case self::TYPE_VKS_UFNS: 
                return 'ВКС с УФНС';
            case self::TYPE_VKS_FNS:
                return 'ВКС с ФНС';
            case self::TYPE_CONFERENCE:
                return 'Собрания';
            case self::TYPE_VKS_EXTERNAL:
                return 'ВКС внешние';
        }
        return $this->type_conference;
    }
    
    
    /**
     * Есть ли события, которые пересекают текущее событие
     * @param boolean $onlyApproved учитывать только согласованные заявки
     * @return AbstractConference
     */
    public function isCrossedMe($onlyApproved=true)
    {
        $dateStart = \Yii::$app->formatter->asDatetime($this->date_start);
        $dateEnd = \Yii::$app->formatter->asDatetime($this->date_end);
        $query = parent::find()
            ->where(['between', 'date_start', $dateStart, $dateEnd])
            ->andWhere(['<>', 'id', $this->id]);
        $places = ['or'];
        foreach ($this->arrPlace as $place) {
            $places[] = ['like', 'place', $place];
        }        
        $query->andWhere($places);
        if ($onlyApproved) {
            $query->andWhere(['status' => self::STATUS_COMPLETE]);
        }
        return $query->one();
    }
    
    /**
     * Есть ли события, которые пересекает это событие
     * @param boolean $onlyApproved учитывать только согласованные заявки
     * @return AbstractConference
     */
    public function isCrossedI($onlyApproved=true)
    {
        $dateStart = \Yii::$app->formatter->asDatetime($this->date_start);        
        $query = parent::find()
            ->where(':date_start between date_start and date_end', [':date_start' => $dateStart])
            ->andWhere(['<>', 'id', $this->id]);
        $places = ['or'];
        foreach ($this->arrPlace as $place) {
            $places[] = ['like', 'place', $place];
        }        
        $query->andWhere($places);
        if ($onlyApproved) {
            $query->andWhere(['status' => self::STATUS_COMPLETE]);
        }
        return $query->one();
    }
    
    /**
     * Описание
     * @return string
     */
    public function getDescription()
    {
        $accessShowAllFields = $this->accessShowAllFields();
        
        $fields = [
            self::TYPE_VKS_UFNS => ['members_people'],
            self::TYPE_VKS_FNS => $accessShowAllFields ? ['members_people'] : [],
            self::TYPE_CONFERENCE => $accessShowAllFields ? ['members_people'] : [],
            self::TYPE_VKS_EXTERNAL => $accessShowAllFields ? ['format_holding', 'responsible', 'platform']: [],
        ];        
        $result = '';
        foreach ($fields[$this->type_conference] as $field) {
            $result .= $this->getAttributeLabel($field) . ': ' . $this->$field . '<br />';
        }
        if ($this->isCrossedI()) {
            $result .= '<i class="fas fa-exclamation-triangle text-danger"></i> Другое событие пересекает это событие<br />';
        }
        if ($this->isCrossedMe()) {
            $result .= '<i class="fas fa-exclamation-circle text-danger"></i> Это событие пересекает другое событие<br />';
        }
        return $result;
    }
    
    /**
     * Ссылка на текущее событие
     * @return string|null
     */
    public function getUrlAdmin()
    {        
        $type = '';
        switch ($this->type_conference) {
            case self::TYPE_VKS_UFNS: 
                $type = 'vks-ufns';
                break;
            case self::TYPE_VKS_FNS:
                $type = 'vks-fns';
                break;
            case self::TYPE_CONFERENCE:
                $type = 'conference';
                break;
            case self::TYPE_VKS_EXTERNAL:
                $type = 'vks-external';
                break;            
        }
        if ($type <> '') {
            return ['/admin/' . $type . '/view', 'id'=>$this->id];
        }
        return null;
    }
    
    /**
     * Псевдоним текущего события
     * @return string
     */
    public function strType()
    {
        $type = null;
        switch ($this->type_conference) {
            case self::TYPE_VKS_UFNS: 
                $type = 'vks-ufns';
                break;
            case self::TYPE_VKS_FNS:
                $type = 'vks-fns';
                break;
            case self::TYPE_CONFERENCE:
                $type = 'conference';
                break;
            case self::TYPE_VKS_EXTERNAL:
                $type = 'vks-external';
                break;            
        }
        return $type;
    }
    
    /**
     * Заголовок
     * @return string
     */
    public function getTitle()
    {
        if (!$this->accessShowAllFields()) {
            return $this->place;
        }
        return "({$this->place}) {$this->theme}";
    }
    
    /**
     * Проверка прав редактирования 
     * @return boolean
     * @throws NotFoundHttpException
     */
    public function isEditor()
    {
        if (($modelTree = Tree::findOne(['module' => static::getModule()])) == null) {
            throw new NotFoundHttpException('Не найден узел с модулем "' . static::getModule() . '"');
        }
        if (Access::checkAccessUserForTree($modelTree->id)) {
            return true;
        }
        return false;
    }
    
    /**
     * Можно ли показывать все поля
     * @return string
     */
    public function accessShowAllFields()
    {        
        return static::isAccess($this->strType());
    }        
    
    /**
     * Проверка прав доступа к указанному типу 
     * @param string $type
     * @return boolean
     */
    public static function isAccess($type)
    {
        // гости не имеют прав
        if (\Yii::$app->user->isGuest) {
            return false;
        }
        
        // админ с полными правами
        if (\Yii::$app->user->can('admin')) {
            //return true;
        }
        
        // остальные пользователи
        $accessParam = isset(Yii::$app->params['conference']['access']) ? Yii::$app->params['conference']['access'] : null;
        
        if (is_array($accessParam) && isset($accessParam[$type])) {
            
            $accessByType = $accessParam[$type];
                
            // 1. Проверка прав на основании [users] - имена учетных записей
            if (isset($accessByType['users']) && self::isAccessUsers($accessByType['users'])) {
                return true;
            } 
            
            // 2. Проверка прав на основании [groups] - группы в базе SQL
            if (isset($accessByType['groups']) && self::isAccessGroups($accessByType['groups'])) {                
                return true;
            }            
            
            // 3. Проверка прав на основании [groups_ad] - группы в Active Directory
            if (isset($accessByType['groups-ad']) && self::isAccessGroupsAd($accessByType['groups-ad'])) {
                return true;
            }  
        }
        
        return false;
    }
    
    /**
     * Проверка входит ли текущий пользователь в указанный список пользователей
     * @param array $users
     * @return boolean
     */
    protected static function isAccessUsers($users)
    {
        if (!is_array($users)) {
            $users = [$users];
        }
        
        foreach ($users as $user) {
            if ($user == Yii::$app->user->identity->username) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Проверка входит ли текущий пользователь в указанные группы из RBAC
     * @param array $groups
     * @return boolean
     */
    protected static function isAccessGroups($groups)
    {
        if (!is_array($groups)) {
            $groups = [$groups];
        }
        
        foreach ($groups as $group) {
            
            if ($group == '?' && \Yii::$app->user->isGuest) {
                return true;
            }
            
            if ($group == '@' && !\Yii::$app->user->isGuest) {
                return true;
            }
            
            if (Yii::$app->user->can($group)) {
                return true;
            }
        }
        return false;
    }
    
    /**
     * Проверка вхождения текущего пользователя в указанные группы (Active Directory)
     * с группыми, которые есть у него в поле memberof
     * @param array $groups
     * @return boolean
     */
    protected static function isAccessGroupsAd($groups)
    {
        if (!is_array($groups)) {
            $groups = [$groups];
        }
        
        foreach ($groups as $group) {
            if (strpos(Yii::$app->user->identity->memberof, $group) !== false) {
                return true;
            }
        }
        return false;
    }

        
}
