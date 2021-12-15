<?php
namespace app\modules\calendar\widgets;

use app\modules\calendar\models\Calendar;
use app\modules\calendar\widgets\DatePickerOnlyCalendar;
use yii\bootstrap4\Dropdown;
use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\web\View;
use yii\widgets\Pjax;

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
     * @var string
     */
    public $idDatePicker = 'ais3-calendar-datepicker';
    
    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();           
    }   

    /**
     * {@inheritdoc}
     */
    public function run()
    {

$urlJson = Url::to(['/calendar/default/json-data', 'date'=>'-date-', 'ver'=>'-ver-']);
$this->view->registerJs(<<<JS
    
    nowDate = new Date();
    window.currentMonthYear = '01.' + getNumWithZero(nowDate.getMonth() + 1) + '.' + getNumWithZero(nowDate.getFullYear());
    
    window.jsonData = '';

    function updateCaledarAis3() {
        url = '$urlJson'.replace('-date-', window.currentMonthYear);
        url = url.replace('-ver-',  (new Date()).getTime());
        $.getJSON(url)
        .done(function(data) {
            jsonData = data;
            $('#{$this->idDatePicker}').parent('div').kvDatepicker('update', convertToISO(window.currentMonthYear));
        });        
    }           

    function convertToISO(date)
    { 
        return date.substring(6,10) + '-' + date.substring(3,5) + '-' + date.substring(0,2);
    }
    
    updateCaledarAis3();
    
    /**
     * Подгонка числа к вдузначному виду
     * @return string
     */
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
                    template: '<div class="popover max-width-50" role="tooltip"><div class="arrow"></div><h3 class="popover-header popover-header-blue"></h3><div class="popover-body"></div></div>'
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

    #{$this->id} .datepicker table tr td.old, #{$this->id} .datepicker table tr td.new {        
        color: #fff !important;
        opacity: 0.4;
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

    .clendar-weekend {
        color: #e45c5c;
    }

    .white-space-normal {
        white-space: normal;
    }

    .calendar-card-header {
        font-size: 0.8rem;
        font-weight: 600;
        margin-bottom: 0;
        color: #666;
    }

    .calendar-badge-danger {
        background-color: #e45c5c;
        font-weight: normal !important;
    }
    .calendar-badge-success {
        background-color: #28a745;
        font-weight: normal !important;
    }
    .calendar-badge-warning {
        background-color: #ffc107;
        font-weight: normal !important;
    }
    .calendar-badge-info {
        background-color: #17a2b8;
        font-weight: normal !important;
    }
    .calendar-badge-primary {
        background-color: #007bff;
        font-weight: normal !important;
    }
    .calendar-badge-secondary {        
        background-color: #6c757d;
        font-weight: normal !important;
    }
    .calendar-badge-dark {
        background-color: #343a40;
        font-weight: normal !important;
    }  

    .max-width-50 {
        max-width: 30%;
    }

CSS);

$js = <<<JS
    function(date) {
        //var dt = date.toLocaleDateString('ru');   - так не работает в IE ((((
        var dt = getNumWithZero(date.getDate()) + '.' + getNumWithZero(date.getMonth() + 1) + '.' + getNumWithZero(date.getFullYear());
               
        if (jsonData.hasOwnProperty(dt)) {           
            var vTitle = jsonData[dt].title;
            var vClasses = jsonData[dt].colorClass;
            var vContent = '';

            for(k in jsonData[dt]['content']) {
                vContent += "<div class='card mb-2'>";
                if (k != '-') {
                    vContent += "<div class='card-header p-1 text-center'><p class='calendar-card-header'>" + k + "</p></div>";
                }
                vContent += "<div class='card-body p-1'>" + jsonData[dt]['content'][k] + "</div></div>";
            }

            return {
                tooltip: vTitle,
                classes: vClasses,
                content: '<div class="day-ais3" data-toggle="tooltip" data-original-title="'
                    + vTitle + '" data-content="' + vContent + '">' + (date.getDate()) + '</div>'
            };
        }
        // выделение выходных дней
        else if (date.getDay() == 6 || date.getDay() == 0) {
            return {
                tooltip: dt,
                //classes: 'border border-danger rounded'
                content: '<div class="day-ais3" style="color: #e45c5c;" data-toggle="tooltip" data-original-title="' 
                    + dt + '" data-content="<span class=\'text-danger\'>Выходной день</span>">' + (date.getDate()) + '</div>'
            };
        }
        $('.popover, .show').popover('hide');
    }
JS;

        Pjax::begin(['timeout'=>false, 'enablePushState'=>false, 'options'=>['id'=>'pjax-datepicker-ais3']]);
        $urlDate = Url::toRoute(['/calendar/default/view', 'date'=>'-date-']);

        $jsChangeDate = new JsExpression("function(d) {
            //var dt = d.date.toLocaleDateString('ru-RU');
            var dt = getNumWithZero(d.date.getDate()) + '.' + getNumWithZero(d.date.getMonth() + 1) + '.' + getNumWithZero(d.date.getFullYear());
            var url = '$urlDate'.replace('-date-', dt);            
            $('.popover, .show').popover('hide');
            modalViewer.showModalManual(url, true, 'get');
        }");

        echo '<div id="' . $this->id . '" class="row">';
        echo '<div class="col">';
        echo DatePickerOnlyCalendar::widget([            
            'type' => DatePickerOnlyCalendar::TYPE_INLINE,
            'name' => 'datepicker_ais3',
            'pluginEvents' => [                
                'changeDate' => $jsChangeDate,
                'changeMonth' => new JsExpression("function(d) { 
                    currentMonthYear = '01.' + getNumWithZero(d.date.getMonth() + 1) + '.' + getNumWithZero(d.date.getFullYear()); 
                    updateCaledarAis3();
                    //console.log(d.date.getMonth()+1); 
                }"),
                //'show' => new JsExpression("function(d) { console.log(d); }"),
            ],                 
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'beforeShowDay' => new JsExpression($js),         
                //'beforeShowMonth' => new JsExpression('function(d) {console.log(d);}'),                                                 
                'todayHighlight' => true,
            ],
            'options' => ['id' => $this->idDatePicker],
        ]);      
        echo '</div>';
        if (Calendar::roleModerator()) {
            echo '<div clas="dropdown">';           
            echo Html::a('<i class="fas fa-ellipsis-v"></i>', null, ['data-toggle'=>'dropdown', 'class' => 'btn btn-sm  text-light']) 
            . Dropdown::widget([
                'items' => [
                        ['label' => 'Типы событий', 'url' => ['/calendar/calendar-types/index'], 'linkOptions'=>['class'=>'mv-link']],                    
                    ],
            ]);
            echo '</div>';
        }
        echo '</div>';

        Pjax::end();
    }

}