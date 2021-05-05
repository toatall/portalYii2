<?php


namespace app\models\christmascalendar;


use yii\db\Query;

class ChristmasCalendar
{
    const DATE_START = '01.12.2020';
    const DATE_END = '31.12.2020';

    /**
     * @var \DateTimeImmutable
     */
    private $dateStart;

    /**
     * @var \DateTimeImmutable
     */
    private $dateEnd;

    /**
     * @var \DateTimeImmutable
     */
    private $dateNow;

    /**
     * @var self
     */
    private static $instance;

    /**
     * ChristmasCalendar constructor.
     * @throws \Exception
     */
    public function __construct()
    {
        $this->init();
    }

    /**
     * Initialize
     * @throws \Exception
     */
    public function init()
    {
        $this->dateStart = new \DateTimeImmutable(self::DATE_START);
        $this->dateEnd = new \DateTimeImmutable(self::DATE_END);
        $this->dateNow = new \DateTimeImmutable();
    }

    /**
     * @return ChristmasCalendar
     * @throws \Exception
     */
    public static function inst()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function allDays()
    {
        $weeks = $this->dateEnd->format('W') - $this->dateStart->format('W');

        $result = [];
        $dateStart = $this->dateStart;
        for ($i=0; $i<=$weeks; $i++) {
            for ($d=1; $d<=7; $d++) {
                if ($dateStart->format('N') == $d && $dateStart <= $this->dateEnd) {
                    $model = $this->findChristmasCalendarByDay($dateStart->format('j'));
                    if ($model !== null) {
                        $result[$dateStart->format('j')] = $this->findChristmasCalendarByDay($dateStart->format('j'));
                    }
                    //$dateStart = $dateStart->add(new \DateInterval('P1D'));
                    $dateStart = $dateStart->modify('+1 day');
                }
            }
        }
        return $result;
    }

    /**
     * @return array|\yii\db\ActiveRecord|null
     */
    public function today()
    {
        if ($this->dateNow >= $this->dateStart && $this->dateNow <= $this->dateEnd) {
            return $this->findChristmasCalendarByDay($this->dateNow->format('j'));
        }
        return null;
    }



    /**
     * @return string
     */
    public function countWeeks()
    {
        return $this->dateEnd->format('W') - $this->dateStart->format('W');
    }


    /**
     * @param $day
     * @return array|\yii\db\ActiveRecord|null
     */
    private function findChristmasCalendarByDay($day)
    {
        $query = ChristmasCalendarQuestion::find()
            ->where(['day' => $day])
            ->one();
        return $query;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateStart()
    {
        return $this->dateStart;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateEnd()
    {
        return $this->dateEnd;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDateNow()
    {
        return $this->dateNow;
    }

    /**
     * Рейтинг пользователей, которые угадали
     * @return array
     * @throws \yii\db\Exception
     */
    public function getRating()
    {
        $query = \Yii::$app->db->createCommand("
            select 
                 isnull(usr.fio, answer.username) fio
                ,count(distinct answer.id) count_answers
                ,row_number() over(order by count(distinct answer.id) desc) #
                ,SUM(CAST(FORMAT(answer.date_create,'HHmmss') as bigint)) as timesum
            from {{%christmas_calendar_answer}} answer
                left join {{%christmas_calendar_question}} question on question.id=answer.id_question
                left join {{%user}} usr on answer.username = usr.username
            where answer.id_user=question.id_user
                and convert(varchar,answer.date_create,112) < convert(varchar,getdate(),112)
            group by isnull(usr.fio, answer.username)
            order by count(distinct answer.id) desc, timesum asc
        ")
        ->queryAll();

        return $query;
    }
}