<?php
/** @var yii\web\View $this */

use yii\helpers\Url;
use app\assets\fullcalendar\FullCalendarAsset;
use app\modules\meeting\assets\MeetingAsset;
use yii\bootstrap5\Html;
use app\modules\meeting\helpers\MeetingHelper;

FullCalendarAsset::register($this);
MeetingAsset::register($this);

$this->title = 'Календарь';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['/meeting/calendar/data']);
?>

<p class="display-4 border-bottom">
    <?= $this->title ?>
</p>    

<div class="card">        
    <div class="card-body">
        <?= Html::beginForm('', '', ['id'=>'form-filter']) ?>
        <?= Html::checkboxList('filterChecked',
            MeetingHelper::allTypes(), // все выбраны по умолчанию
            MeetingHelper::allTypesLabelsWithBadgeColors(), // список всех типов мероприятий
            ['encode' => false, 'id'=>'filer-type-conference']
        ) ?>
    </div>
    <?= Html::endForm() ?>
</div>

<div id="script-warning" class="alert alert-danger mt-2"></div>
<div id="loading" class="mt-2">
    <i class="fas fa-circle-notch fa-spin"></i> Загрузка событий...    
</div>

<div id="calendar"></div>

<?php 
$this->registerJs(<<<JS
        
    function filterType() {        
        var vals = []
        $('#filer-type-conference input:checked').each(function() {
            vals.push(this.value)
        })
        return vals
    }
    
    const modalCalendar = new ModalViewer()
        
    const calendarEl = document.getElementById('calendar')

    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'ru',
        initialView: 'timeGridWeek',
        eventClick: function(info) {
            const item = info.event
            if (item.url) {
                modalCalendar.showModal(item.url)
                info.jsEvent.preventDefault()
            }
        },
        // событие добавления каждого события
        eventDidMount: function(event) {
            const el = $(event.el)
            el.popover({
                container: 'body',
                trigger: 'hover',
                placement: 'auto',
                title: event.event.extendedProps.fullTitle,
                content: event.event.extendedProps.description,
                html: true,
                customClass: 'popover-calendar',
            })
            el.find('.fc-event-title').html(event.event.title)
            el.find('.fc-list-event-title').find('a').html(event.event.title)           
        },        
        // eventRender: function(event, element) {
        //     console.log(element)
        // },
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
            failure: function(e) {
                const cont = $('#script-warning')
                cont.html(
                    'Error: ' + e.message + '<br />' + 
                    'Status: ' + e.xhr.status + '<br />' + 
                    'Status text: ' + e.xhr.statusText)
                cont.show()
                console.log(e)
            },            
        },
        loading: function(bool) {
            $('#loading').toggle(bool)        
        }
    })

    calendar.render();    
                
    $('#filer-type-conference input:checkbox').on('change', function() {       
        calendar.removeAllEventSources();
        calendar.addEventSource({ 
            url: '$url',
            extraParams: { 
                filterType: filterType(),
            },
            failure: function(e) {
                const cont = $('#script-warning')
                cont.html(
                    'Error: ' + e.message + '<br />' + 
                    'Status: ' + e.xhr.status + '<br />' + 
                    'Status text: ' + e.xhr.statusText)
                cont.show()
                console.log(e)
            }, 
        });
    });
    
    $('.fc-license-message').hide();
JS
); 

?>