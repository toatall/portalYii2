<?php
namespace app\widgets;

use Yii;
use kartik\date\DatePicker;
use yii\caching\DbQueryDependency;
use yii\db\Expression;
use yii\db\Query;
use yii\web\JsExpression;
use yii\web\View;

/**
 * Календарь в шапке сайта
 */
class DatePickerCalendarAis3 extends \yii\bootstrap\Widget
{

    /**
     * @var string
     */
    public $id = 'ais3-calendar';
    
    /**
     * @var mixed
     */
    private $data;

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();      
        $this->loadData();
    }

    /**
     * Загрузка данных из базы
     */
    public function loadData()
    {               
        $resultQuery = Yii::$app->cache->getOrSet('calendar_cache_' . Yii::$app->user->identity->current_organization, function() {
            return $this->baseQuery()->all();
        }, 0, new DbQueryDependency([
            'query' => $this->baseQuery()->orderBy([])->select('max(cal.date_update + cal_data.date_update)'),
        ]));

        $this->data = json_encode($this->prepareData($resultQuery));
    }

    /**
     * Подготовка данных
     * @return array
     */
    private function prepareData($data)
    {
        $result = [];

        foreach ($data as $item) {
            
            $date = Yii::$app->formatter->asDate($item['date']);           
            $string = "<span class='badge badge-{$item['descr_color']} f-size-08'>{$item['description']}</span>";

            if (isset($result[$date])) {
                $result[$date]['content'] .= '<br />' . $string;                
            }
            else {
                $result[$date] = [
                    'title' => $date,
                    'content' => $string,
                    'colorClass' => 'badge badge-' . $item['date_color'] . ' d-table-cell fa-1x',
                ];
            }

        }
              
        return $result;        
    }

    /**
     * Настройка базового запроса (с определенными фильтрами, сортировкой, ...)
     * @return Query
     */
    private function baseQuery()
    {
        return (new Query())
            ->select('cal.id, cal.date, cal.color as date_color, cal_data.description, cal_data.color as descr_color, cal_data.is_global')
            ->from('{{%calendar}} cal')
            ->leftJoin('{{%calendar_data}} cal_data', 'cal.id = cal_data.id_calendar')
            ->where(['>=', 'cal.date', new Expression('DATEADD(YEAR,-1,getdate())')])
            ->andWhere(['cal.date_delete' => null])
            ->andWhere(['not', ['cal_data.id' => null]])
            ->andWhere(['or', 
                ['cal.code_org' => Yii::$app->user->identity->current_organization ?? null],
                ['cal_data.is_global' => 1],
            ])
            ->orderBy([                
                'cal_data.is_global' => SORT_ASC, // сначала не глобальные, потом глобальные
                'cal_data.sort' => SORT_DESC, // сотрировка события
                'cal_data.date_create' => SORT_ASC, // дата создания
            ]);
    }


    /**
     * {@inheritdoc}
     */
    public function run()
    {
$this->view->registerJs(<<<JS
    
    $('input[name="datepicker_ais3"]').hide();
    
    // данные из файла
    jsonData = $this->data;    

    function getNumWithZero(val)
    {
        return ((val <= 9) ? '0' : '') + val.toString();
    }

    $('body').on({
        mouseenter: function() {
            var t = $(this);
            var day = t.find('.day-ais3');
            if (day.length) {
                $(this).popover({
                    content: day.data('content'),
                    title: day.data('original-title'),                     
                    html: true,
                    template: '<div class="popover popover-max-width" role="tooltip"><div class="arrow"></div><h3 class="popover-header popover-header-blue"></h3><div class="popover-body"></div></div>'
                }).popover('show');
            }
        },
        mouseleave: function() {
            $(this).popover('hide');
        }
    }, '.datepicker .day:not(.disabled)');

JS, View::POS_END);

$this->view->registerCss(<<<CSS
    
    #{$this->id} table tr td, #{$this->id} .datepicker table tr th {
        height: 1rem !important;
    }

    #{$this->id} .datepicker table tr td span {
        height: 3rem !important;
        line-height: 3rem !important;
    }

    #{$this->id} .datepicker {
        font-size: 0.85rem !important;
        color: white !important;
    }

    #{$this->id} .datepicker table tr td.old, .datepicker table tr td.new {
        color: #aaa !important;
    }

    #{$this->id} .datepicker table tr td.day:hover,
    #{$this->id} .datepicker table tr td.focused {
        background: #1163ad;
        cursor: pointer;
    }

    #{$this->id} .datepicker .datepicker-switch:hover,
    #{$this->id} .datepicker .prev:hover,
    #{$this->id} .datepicker .next:hover,
    #{$this->id} .datepicker tfoot tr th:hover {
        background: #eeeeee;
        color: #000;
    }

    #{$this->id} .datepicker table tr td span:hover,
    #{$this->id} .datepicker table tr td span.focused {
        background: #eeeeee;
        color: #000;
    }

CSS);

$js = <<<JS
    
    function(date) {
        //var dt = date.toLocaleDateString('ru');   - так не работает в IE ((((
        var dt = getNumWithZero(date.getDate()) + '.' + getNumWithZero(date.getMonth() + 1) + '.' + getNumWithZero(date.getFullYear());
                
        if (jsonData.hasOwnProperty(dt)) {
            return {
                tooltip: jsonData[dt].title,
                classes: jsonData[dt].colorClass,
                content: '<div class="day-ais3" data-toggle="tooltip" data-original-title="'
                    + jsonData[dt].title + '" data-content="' + jsonData[dt].content + '">' + (date.getDate()) + '</div>'
            };
        }
        $('.popover, .show').popover('hide');
    }
JS;

       
        echo '<div id="' . $this->id . '">';
        echo DatePicker::widget([
            'type' => DatePicker::TYPE_INLINE,
            'name' => 'datepicker_ais3',
            'pluginEvents' => [
                //'changeDate' => new JsExpression("function(d) { var dt = d.date.toLocaleDateString('ru'); modalViewer.showModalManual('/calendar/date-view', true, 'get', { date: dt, id: 234 }); } "),  
                //'changeMonth' => new JsExpression("function(d) { console.log(d.date.getMonth()+1); }"),
                //'show' => new JsExpression("function(d) { console.log(d); }"),
            ],                 
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'beforeShowDay' => new JsExpression($js),         
                //'beforeShowMonth' => new JsExpression('function(d) {console.log(d);}'),                                                 
                'todayHighlight' => true,
            ],
        ]);      
        echo '</div>';
    }

}