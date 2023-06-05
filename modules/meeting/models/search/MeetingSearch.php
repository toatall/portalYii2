<?php
namespace app\modules\meeting\models\search;

use app\modules\meeting\models\ar\ARMeeting;
use app\modules\meeting\models\Meeting;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;

/**
 * Базовый класс фильтрации таблицы {{%meeting}}
 */
abstract class MeetingSearch extends ARMeeting
{

    /**
     * Ограничение на отображение записей
     * по количеству дней от текущей даты
     * @var int 
     */
    public $between_days = 0;

    /**
     * {@inheritDoc}
     */
    public function rules(): array
    {
        return [             
            [['id', 'between_days'], 'integer'],
            [['duration', 'date_start', 'theme', 'place', 'members_people', 'responsible', 'members_organization'], 'safe'],
        ];
    }

    /**
     * @param $params
     * @return ActiveDataProvider
     */
    public function search($params): ActiveDataProvider
    {        
        $this->load($params);

        $query = $this->filterActual($this->query());

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'date_start' => SORT_DESC,
                ],
            ],
        ]);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $this->filterMain($query);

        // ограничить по количеству дней (не менее и не более {{between_days}} от текущей даты)
        if ($this->between_days > 0) {
            $query->andWhere(['between', 
                'date_start', time() - ($this->between_days * 60 * 60 * 24),
                time() + ($this->between_days * 60 * 60 * 24)]);
        }

        return $dataProvider;
    }    

    /**
     * Базовая функция для создания запроса
     *
     * @return ActiveQueryInterface
     */
    protected function query(): ActiveQueryInterface
    {
        return static::find();
    }

    /**
     * Фильтрация по типу
     *        
     * @param ActiveQueryInterface $query
     * @return ActiveQueryInterface
     */
    protected function filterType(ActiveQueryInterface $query): ActiveQueryInterface
    {
        return $query->andWhere([
            'type' => $this->modelClass()::getType(),
        ]);
    }    

    /**
     * Добавление фильтров в запрос
     *
     * @param ActiveQueryInterface $query
     * @return ActiveQueryInterface
     */
    protected function filterMain(ActiveQueryInterface $query): ActiveQueryInterface
    {
        $this->filterType($query);
        return $query;
    }

    /**
     * Не удаленные
     *
     * @param ActiveQueryInterface $query
     * @return ActiveQueryInterface
     */
    protected function filterActual(ActiveQueryInterface $query): ActiveQueryInterface
    {
        return $query->andWhere(['date_delete' => null]);
    }

    /**
     * Функция для поиска в календаре
     * 
     * @return MeetingSearch[]|null
     */
    public function findPublic($dateStart, $dateEnd)
    {
        $query = static::find()
            ->where('{{date_start}} BETWEEN :date_start AND :date_end', [
                ':date_start' => $dateStart,
                ':date_end' => $dateEnd,
            ])
            ->indexBy('id');
        $this->filterActual($query);
        return $this->filterMain($query)->all();
    }

    /**
     * Класс базовой модели
     * 
     * @return Meeting|string
     */
    abstract public static function modelClass(); 

    /**
     * Цвет события (для отображения в календаре)
     * 
     * @return string
     */    
    public static function getColor()
    {
        return 'meeting-bg-' . static::modelClass()::getType();
    }

    /**
     * Описание 
     * 
     * @param bool $short короткий формат вывода
     * @return string
     */
    public function getDescription($short = false)
    {
        if ($short) {
            return null;
        }
        $result = '';

        $result .= sprintf('<span class="badge fa-1x mb-2 %s">%s</span><br />', 
            static::getColor(),
            static::modelClass()::getTypeLabel());

        return $result . sprintf('<b>%s:</b> %s <br />', 
            $this->getAttributeLabel('members_people'),
            \Yii::$app->formatter->asText($this->members_people));
    }


}