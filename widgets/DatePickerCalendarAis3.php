<?php
namespace app\widgets;

use Yii;
use kartik\date\DatePicker;
use yii\web\JsExpression;
use yii\web\View;

class DatePickerCalendarAis3 extends \yii\bootstrap\Widget
{

    /**
     * @var string
     */
    public $id = 'ais3-calendar';

    /**
     * Файл с данными о датах для календаря
     * @var string
     */
    public $jsonFile = '@app/web/public/content/portal/calendar-ais3/data.json';

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
        $this->loadJsonFile();
    }

    /**
     * Загрузка файла
     */
    private function loadJsonFile()
    {
        $file = Yii::getAlias($this->jsonFile);        
        if (file_exists($file)) {
            $this->data = file_get_contents($file);
        }
        if (!$this->data) {
            $this->data = json_encode('');
        }
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
                'changeDate' => new JsExpression("function(d) { console.log(d.date.toLocaleDateString('ru')); }"),  
                //'changeMonth' => new JsExpression("function(d) { console.log(d.date.getMonth()+1); }"),
                //'show' => new JsExpression("function(d) { console.log(d); }"),                             
            ],                 
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd',
                'beforeShowDay' => new JsExpression($js),                                                          
                'todayHighlight' => true,
            ],
        ]);      
        echo '</div>';
    }

}