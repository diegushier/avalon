<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use app\assets\AppAsset;
use yii\helpers\Url;

AppAsset::register($this);
$urllibros = Url::to(['notificacioneslibros/checker']);
$urlshows = Url::to(['notificacionesshows/checker']);
$js = <<<EOT

ref = window.location.href;
site = ['libros', 'shows']
id = ref.includes('libros') ? '#w2' : ref.includes('shows') ? '#w2' : '#w1'

$(id).append(
    "<li class='nav-item'  id='output-notif'>"+
    "<div class='btn-group'>"+
        "<button class='btn btn-secondary btn-sm dropdown-toggle' type='button' data-toggle='dropdown' aria-haspopup='true' aria-expanded='true'>"+
            "<i class='fas fa-bullhorn'></i>"+
        "</button>"+
        "<div class='dropdown-menu' style='width: 200px;' id='notif'>"+
            "<div class='alert alert-warning'>No hay novedades</div>"+
        "</div>"+
    "</div>"+
    "</li>");


$.ajax({
    method: 'GET',
    url: '$urllibros',
    data: {},
    success:function(data = null) {
        if (data != null) {
            $('#notif').empty()
            $.each(data, (k, v) => {
                $('#notif').append(
                    "<a class='p-1 a-links' style='font-size: 10px' href='index.php?r=libros%2Fview&id="+ v['libro_id'] +"'>"+v['mensaje']+"</a>"
                )
            })
        }
    },
    error: () => {
    }
})

$.ajax({
    method: 'GET',
    url: '$urlshows',
    data: {},
    success:function(data = null) {
        if (data != null) {
            $('#notif').empty()
            $.each(data, (k, v) => {
                $('#notif').append(
                    "<a class='p-1 a-links' style='font-size: 10px' href='index.php?r=shows%2Fview&id="+ v['show_id'] +"'>"+v['mensaje']+"</a>"
                )
            })
        }
    },
    error: () => {
    }
})
EOT;

if (isset(Yii::$app->user->identity)) {
    $this->registerJs($js);
    $this->registerCss('.a-links:hover { text-decoration: none; }');
}

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => 'Avalon',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-dark bg-dark navbar-expand-md fixed-top',
                'style' => 'background-color: #3E3F3A !important;'
            ],
            'collapseOptions' => [
                'class' => 'justify-content-end',
            ],
        ]);

        $menu = [];

        if (!(Yii::$app->user->isGuest)) {
            $menu += [
                ['label' => 'Libros', 'url' => ['/libros/index']],
                ['label' => 'Peliculas', 'url' => ['/shows/peliculas']],
                ['label' => 'Series', 'url' => ['/shows/series']]
            ];
        }

        if (Yii::$app->user->isGuest) {
            $menu += [
                ['label' => 'Login', 'url' => ['/site/login']],
            ];
        } else {
            $menu[] = ['label' => 'Desconectar', 'url' => ['/site/logout'], 'linkOptions' => ['data-method' => 'post']];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => $menu,
        ]);
        NavBar::end();
        ?>

        <div id="back" class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>
    <!-- 
    <footer class="footer bg-dark">
        <div class="container">
            <p class="float-left">&copy; Proyecto Final Integrado Avalon <?= date('Y') ?></p>
        </div>
    </footer> -->

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>