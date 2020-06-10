<?php

/* @var $this yii\web\View */

use app\models\Libros;
use yii\bootstrap4\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Avalon';

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
calendar.setOption('height', 650);
EOT;

$librosJS = Json::encode($libros);
$showsJS = Json::encode($shows);

$js = <<<EOT

var libros = $librosJS;
var shows = $showsJS;
var librosId = [];
var showsId = [];

shows.forEach(k => {
    var index = $('#cine_' + k.id)
    var nombre = index.text();
    if (nombre.length >= 9) {
        index.text(nombre.substr(0, 9) + '...')
        index.mouseover(() => {
            index.text(nombre)
        })
        
        index.mouseout(() => {
            index.text(nombre.substr(0, 9) + '...')
        });
    }
})

libros.forEach(k => {
    var index = $('#libro_' + k.id)
    var nombre = index.text();
    if (nombre.length >= 9) {
        index.text(nombre.substr(0, 9) + '...')
        index.mouseover(() => {
            index.text(nombre)
        })

        index.mouseout(() => {
            index.text(nombre.substr(0, 9) + '...')
        });
    }
})

2

EOT;


$css = '
    .alert-own {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    } 
';


$this->registerJs($js);
$this->registerCss($css);
$this->registerJs($googleCalendar);
?>

<div id="left" class="site-index row m-auto">
    <div class="col-sm-12 col-lg-2 lg-border-right">
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
    <div id="center" class="col-sm-12 col-lg-5 lg-border-right container lg-sm-2">
        <h5 class="text-center"> Novedades...</h5>
        <div id="libros_sug" class="row m-1">
            <h6 class="col-12 text-center btn btn-dark text-light">Libros</h6>
            <?php foreach ($libros as $k) : ?>
                <div class="col-lg-6 col-sm-3 d-flex justify-content-center">
                    <div class="card mt-2" style="width: 15rem;">
                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgLibrosUrl/' . $k->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.jpg') ?>'" alt="Card image cap">
                            <?= Html::a(
                                $k->nombre,
                                ['shows/view', 'id' => $k->id],
                                [
                                    'class' => 'btn btn-dark btn-block card-body d-flex flex-column text-light linkdata',
                                    'style' => 'font-size: 8px;',
                                    'id'  => 'libro_' . $k->id
                                ]
                            ) ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
        <br>
        <br>
        <div id="shows_sug" class="row m-1">
            <h6 class="col-12 text-center btn btn-dark text-light">Cine</h6>
            <?php foreach ($shows as $k) : ?>
                <div class="col-lg-6 col-sm-3 d-flex justify-content-center">
                    <div class="card mt-2" style="width: 15rem;">
                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgCineUrl/' . $k->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.jpg') ?>'" alt="Card image cap">
                            <?= Html::a(
                                $k->nombre,
                                ['shows/view', 'id' => $k->id],
                                [
                                    'class' => 'btn btn-dark btn-block card-body d-flex flex-column text-light linkdata',
                                    'style' => 'font-size: 8px;',
                                    'id'  => 'cine_' . $k->id
                                ]
                            ) ?>

                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
    <div id="right" class="col-sm-12 col-lg-4">
        <h5 class="text-center">Nuevos Estrenos....</h5>
        <div id="calendar"></div>
    </div>
</div>