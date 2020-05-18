<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;

$this->title = 'Avalon';
$this->registerCss('

');
$this->registerCssFile('@web/js/packages/core/main.css');
$this->registerCssFile('@web/js/packages/daygrid/main.css');
$this->registerJsFile('@web/js/packages/core/main.js');
$this->registerJsFile('@web/js/packages/daygrid/main.js');
$this->registerJsFile('@web/js/packages/google-calendar/main.js');

$api = getenv('apikey');
$calId = getenv('calendarId');

$googleCalendar = <<<EOT
var calendarEl = document.getElementById('calendar');
var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: [ 'dayGrid', 'googleCalendar' ],
    googleCalendarApiKey: '$api',
    events: {
        googleCalendarId: '$calId'
    },
    eventClick: function(info) {
        info.jsEvent.preventDefault(); // don't let the browser navigate
    
        if (info.event.url) {
            //do nothing
        }
      }
});
calendar.render();
EOT;

$this->registerJs($googleCalendar);
?>

<div class="site-index row m-auto">
    <div class="col-sm-12 col-lg-2 border-right">
        <?= Html::a(
            'Perfil',
            ['/usuarios/view'],
            [
                'class' => 'btn btn-orange w-100 mt-1',
            ]
        ) ?>
        <?= Html::a(
            'Tu lista',
            ['/usuarios/lista'],
            [
                'class' => 'btn btn-orange w-100 mt-1',
            ]
        ) ?>
    </div>
    <div class="col-sm-12 col-lg-9">
        <h3>Nuevos Estrenos....</h3>
        <div id="calendar"></div>
    </div>
</div>