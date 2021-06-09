<?php
/* @var $this \yii\web\View */
use yii\helpers\Url;
use app\assets\fullcalendar\FullCalendarAsset;
use yii\bootstrap\Html;
use kartik\date\DatePicker;

FullCalendarAsset::register($this);

$this->title = 'По месту проведения';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['/conference/calendar-data']);
$urlResources = Url::to(['/conference/resources']);
?>

<h1><?= $this->title ?></h1>
<hr />

<div class="panel panel-defaul">   
    <div class="panel-body">    
        <div style="width:285px;">Дата
        <?= DatePicker::widget([
            'name' => 'date-picker',
            'id' => 'date-picker',  
            'pluginOptions' => [
                'autoclose' => true,
            ],
        ]) ?>           
        </div>
    </div>
</div>
<div id="loading">
    <i class="fas fa-circle-notch fa-spin"></i> Загрузка событий...    
</div>
<div id="script-warning" class="alert alert-danger">
    <code><?= $url ?></code> must be running.
</div>

<div id="calendar"></div>
<?php $this->registerJs(<<<JS
        
    var calendarEl = document.getElementById('calendar');
        
    function convertUrl(date1, date2)
    {
        date1 = date1.toLocaleDateString();
        date2 = date2.toLocaleDateString();
        var url = '$url';
        if (url.indexOf('?') >= 0) {
            url += '&';
        }
        else {
            url += '?';
        }
        url += 'start=' + date1 + '&end=' + date2;
        return url;
    }
        
    function asDate(str)
    {
        var arr = str.split('.');
        console.log(new Date(arr[2], arr[1], arr[0]));
        return new Date(arr[2], arr[1]-1, arr[0]);
    }
        
    function addDay(date)
    {
        var result = new Date(date);
        result.setDate(result.getDate() + 1);
        return result;
    }
        
        
    $('#date-picker').on('change', function() {        
        var start = asDate($(this).val());
        var end = addDay(start);                
        var url = convertUrl(start, end);       
        calendar.gotoDate(start);
    });
        
        
    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'ru',
        initialView: 'resourceTimelineDay',
        eventClick: function(info) {
            var item = info.event;
            if (item.url) {
                modalViewer.showModalManual(item.url);
                info.jsEvent.preventDefault();
            }
        },
        // событие добавления каждого события
        eventDidMount: function(event) {            
            var el = $(event.el);
            el.popover({ 
                container: 'body',
                trigger: 'hover',
                placement: 'auto bottom',
                title: event.event.extendedProps.fullTitle,                
                content: event.event.extendedProps.description,
                html: true
            });            
            el.find('.fc-event-title').html(event.event.title);
            el.find('.fc-list-event-title').find('a').html(event.event.title);          
        }, 
        events: {            
            url: '$url', 
            success: function(content, xhr) {
                $('#date-picker').val(calendar.getDate().toLocaleDateString());                
            },
            failure: function() {
                $('#script-warning').show();
            }, 
        },
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'resourceTimelineDay,resourceTimelineWeek'
        },
        editable: false,
        navLinks: true, // can click day/week names to navigate views
        dayMaxEvents: true, // allow "more" link when too many events
        selectable: true,
        businessHours: {
            daysOfWeek: [1,2,3,4,5,6,7],
            startTime: '09:00',
            endTime: '18:00',
        },
        nowIndicator: true,
        slotMinTime: '08:00:00',
        slotMaxTime: '20:00:00',
        resourceAreaColumns: [
            { field: 'title', headerContent: 'Кабинеты' }
        ],
        resources: '$urlResources',
        loading: function(bool) {
            $('#loading').toggle(bool);            
        }
    });

    calendar.render();
    
    $('.fc-license-message').hide();
        
JS
);

$this->registerCss(<<<CSS
    #script-warning {
        display: none;       
    }

    #loading {
        display: none;        
        top: 10px;
        right: 10px;
    }

    #calendar {        
        margin: 40px auto;
        padding: 0 10px;
    }
CSS
);

?>