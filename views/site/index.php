<?php

/* @var $this yii\web\View */

$this->title = 'Avalon';
$this->registerCss('

');
$this->registerCssFile('@web/js/packages/core/main.css');
$this->registerCssFile('@web/js/packages/daygrid/main.css');
$this->registerJsFile('@web/js/packages/core/main.js');
$this->registerJsFile('@web/js/packages/daygrid/main.js');
$this->registerJsFile('@web/js/packages/google-calendar/main.js');

$js = "
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            plugins: [ 'dayGrid', 'googleCalendar' ],
            googleCalendarApiKey: '" . getenv('apikey') . "',
            events: {
                googleCalendarId: '" . getenv('calendarId') . "'
            },
            eventClick: function(info) {
                info.jsEvent.preventDefault(); // don't let the browser navigate
            
                if (info.event.url) {
                    //do nothing
                }
              }
        });
        calendar.render();
";

$this->registerJs($js);
?>

<div class="site-index">
    <div id="calendar"></div>
</div>