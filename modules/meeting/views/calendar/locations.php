<?php
/** @var yii\web\View $this */

use yii\helpers\Url;
use app\assets\fullcalendar\FullCalendarAsset;
use app\modules\meeting\assets\MeetingAsset;
use kartik\date\DatePicker;

FullCalendarAsset::register($this);
MeetingAsset::register($this);

$this->title = 'По месту проведения';
$this->params['breadcrumbs'][] = $this->title;

$url = Url::to(['/meeting/calendar/data']);
$urlResources = Url::to(['/meeting/location/data']);
?>

<p class="display-4 border-bottom">
    <?= $this->title ?>
</p>    

<div class="">   
    <div class="card card-body" style="width:285px;">    
        <div>
            <b>Дата</b>
            <?= DatePicker::widget([
                'name' => 'date-picker',
                'id' => 'date-picker',  
                'pluginOptions' => [
                    'autoclose' => true,
                    'todayHighlight' => true,
                ],
                'options' => [
                    'autocomplete' => 'off',
                ],
            ]) ?>
        </div>
    </div>
</div>
<div id="loading">
    <i class="fas fa-circle-notch fa-spin"></i> Загрузка событий...    
</div>
<div id="script-warning" class="alert alert-danger mt-2"></div>

<div id="calendar"></div>

<?php 
$this->registerJs(<<<JS
        
    const calendarEl = document.getElementById('calendar')
        
    function convertUrl(date1, date2) {
        date1 = date1.toLocaleDateString();
        date2 = date2.toLocaleDateString();
        let url = '$url';
        if (url.indexOf('?') >= 0) {
            url += '&';
        }
        else {
            url += '?';
        }
        url += 'start=' + date1 + '&end=' + date2;
        return url;
    }
        
    function asDate(str) {
        const arr = str.split('.')
        return new Date(arr[2], arr[1]-1, arr[0])
    }
        
    function addDay(date) {
        const result = new Date(date)
        result.setDate(result.getDate() + 1)
        return result
    }
                
    $('#date-picker').on('change', function() {        
        const start = asDate($(this).val());
        const end = addDay(start);                
        const url = convertUrl(start, end);       
        calendar.gotoDate(start);
    })

    const modalCalendarLocation = new ModalViewer()
                
    const calendar = new FullCalendar.Calendar(calendarEl, {
        locale: 'ru',
        initialView: 'resourceTimelineDay',
        eventClick: function(info) {
            const item = info.event
            if (item.url) {
                modalCalendarLocation.showModal(item.url)              
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
                html: true
            })          
            el.find('.fc-event-title').html(event.event.title)
            el.find('.fc-list-event-title').find('a').html(event.event.title)
        }, 
        events: {            
            url: '$url', 
            success: function(content, xhr) {
                $('#date-picker').val(calendar.getDate().toLocaleDateString())
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
            $('#loading').toggle(bool)         
        }
    });

    calendar.render()
    
    $('.fc-license-message').hide()
        
JS
);

?>