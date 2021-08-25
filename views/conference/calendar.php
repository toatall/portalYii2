<?php
/** @var yii\web\View $this */

use yii\helpers\Url;
use app\assets\fullcalendar\FullCalendarAsset;
use yii\bootstrap4\Html;
use app\models\conference\EventsAll;

FullCalendarAsset::register($this);


$this->title = 'Календарь событий';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['/conference/calendar-data']);
?>

<div class="col border-bottom mb-2">
    <p class="display-4">
    <?= $this->title ?>
    </p>    
</div>    

<div class="card">        
    <div class="card-body">
        <?= Html::beginForm('', '', ['id'=>'form-filter']) ?>
        <?= Html::checkboxList('filterChecked', [EventsAll::TYPE_VKS_UFNS, EventsAll::TYPE_VKS_FNS, EventsAll::TYPE_CONFERENCE, EventsAll::TYPE_VKS_EXTERNAL], [
            EventsAll::TYPE_VKS_UFNS => '<span class="badge text-white" style="background-color:' . EventsAll::COLOR_VKS_UFNS . ';">ВКС с УФНС</span>',
            EventsAll::TYPE_VKS_FNS => '<span class="badge text-white" style="color:white; background-color:' . EventsAll::COLOR_VKS_FNS . ';">ВКС с ФНС</span>',
            EventsAll::TYPE_CONFERENCE => '<span class="badge text-white" style="color:white; background-color:' . EventsAll::COLOR_CONFERENCE . ';">Собрания</span>',
            EventsAll::TYPE_VKS_EXTERNAL => '<span class="badge text-white" style="color:white; background-color:' . EventsAll::COLOR_VKS_EXTERNAL . ';">ВКС внешние</span>',
        ], ['encode' => false, 'id'=>'filer-type-conference']); ?>
    </div>
    <?= Html::endForm() ?>
</div>

<div id="script-warning" class="alert alert-danger">
    <code><?= $url ?></code> must be running.
</div>

<div id="loading">
    <i class="fas fa-circle-notch fa-spin"></i> Загрузка событий...    
</div>
<div id="calendar"></div>

<?php $this->registerJs(<<<JS
        
    function filterType()
    {        
        var vals = [];
        $('#filer-type-conference input:checked').each(function() {
            vals.push(this.value);
        });
        return vals;
    }
        
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'ru',
        initialView: 'timeGridWeek',
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
            //alert(event.event.title);
            el.find('.fc-event-title').html(event.event.title);
            el.find('.fc-list-event-title').find('a').html(event.event.title);          
        },        
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
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
        events: {
            url: '$url',
            extraParams: { 
                filterType: filterType(),
            },
            failure: function() {
                $('#script-warning').show();
            },            
        },
        loading: function(bool) {
            $('#loading').toggle(bool);            
        }
    });

    calendar.render();    
                
    $('#filer-type-conference input:checkbox').on('change', function() {       
        calendar.removeAllEventSources();
        calendar.addEventSource({ 
            url: '$url',
            extraParams: { 
                filterType: filterType(),
            },
            failure: function() {
                $('#script-warning').show();
            }, 
        });
    });
    
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