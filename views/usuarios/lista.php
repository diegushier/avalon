<?php

/* @var $this yii\web\View */

use Symfony\Component\VarDumper\VarDumper;
use yii\bootstrap4\Html;

$this->title = 'Mi lista';
$a = true;
$b = true;
$c = true;

$js = <<<EOT
$('#seguimiento').fadeOut(0);
$('#vistos').fadeIn(1000);

$("#list-1-list").click(() => {
    $('#seguimiento').fadeOut(0);
    $('#vistos').fadeIn(1000);
})

$("#list-2-list").click(() => {
    $('#vistos').fadeOut(0);
    $('#seguimiento').show(1000);
})

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

?>

<div class="site-index row m-auto">
    <div class="col-sm-12 col-lg-2 border-right">
        <div>
            <?= Html::a(
                'Calendario',
                ['/site/index'],
                [
                    'class' => 'btn btn-orange w-100',
                ]
            ) ?>
            <?= Html::a(
                'Perfil',
                ['/usuarios/view'],
                [
                    'class' => 'btn btn-orange w-100 mt-1',
                ]
            ) ?>
        </div>
        <br>

        <div class="list-group" id="list-tab" role="tablist">
            <?php if (isset($librosVisto) || isset($showVisto)) : ?>
                <a class="list-group-item list-group-item-action active" id="list-1-list" data-toggle="list" href="#vistos" role="tab" aria-controls="home">Vistos</a>
            <?php endif ?>
            <?php if (isset($librosVisto) || isset($showVisto)) : ?>
                <a class="list-group-item list-group-item-action" id="list-2-list" data-toggle="list" href="#seguimiento" role="tab" aria-controls="profile">Siguiendo</a>
            <?php endif ?>
        </div>
    </div>

    <div class="col-sm-12 col-lg-9 ">
        <?php if (!(isset($librosVisto) || isset($librosSeg) || isset($showVisto) || isset($showSeg))) : ?>
            <div class="alert alert-own">
                No estas siguiendo nada ni has visto nada aun...
            </div>
        <?php endif ?>

        <div class="tab-content" id="nav-tabContent">
            <?php if (isset($librosVisto) || isset($showVisto)) : ?>
                <div class="tab-pane fade show active" id="vistos" role="tabpanel" aria-labelledby="list-1-list">
                    <?php if (isset($librosVisto)) : ?>
                        <div>
                            <h4>Tus libros: </h4>
                            <hr>
                            <div class="row">
                                <?php foreach ($librosVisto as $k) : ?>
                                    <div class="card col-md-2 border-0">
                                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgLibrosUrl/' . $k->libro->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'" alt="Card image cap">

                                        <div class="card-img-overlay card-body d-flex flex-column">
                                            <?= Html::a(
                                                $k->libro->nombre,
                                                ['libros/view', 'id' => $k->libro->id],
                                                [
                                                    'class' => 'btn btn-dark btn-block mt-auto card-text  text-light',
                                                    'style' => 'font-size: 8px;'
                                                ]
                                            ) ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if (isset($showVisto)) : ?>
                        <br><br>
                        <div>
                            <h4>Tu cine y series: </h4>
                            <hr>
                            <div class="row">
                                <?php foreach ($showVisto as $k) : ?>
                                    <div class="card col-md-2 border-0">
                                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgCineUrl/' . $k->objetos->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'" alt="Card image cap">

                                        <div class="card-img-overlay card-body d-flex flex-column">
                                            <?= Html::a(
                                                $k->objetos->nombre,
                                                ['shows/view', 'id' => $k->objetos->id],
                                                [
                                                    'class' => 'btn btn-dark btn-block mt-auto card-text  text-light',
                                                    'style' => 'font-size: 8px;'
                                                ]
                                            ) ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            <?php endif ?>
            <?php if (isset($librosSeg) || isset($showSeg)) : ?>
                <div class="tab-pane fade show active" id="seguimiento" role="tabpanel" aria-labelledby="list-2-list">
                    <?php if (isset($librosSeg)) : ?>
                        <div>
                            <h4>Tus libros: </h4>
                            <hr>
                            <div class="row">
                                <?php foreach ($librosSeg as $k) : ?>
                                    <div class="card col-md-2 border-0">
                                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgLibrosUrl/' . $k->libro->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'" alt="Card image cap">

                                        <div class="card-img-overlay card-body d-flex flex-column">
                                            <?= Html::a(
                                                $k->libro->nombre,
                                                ['libros/view', 'id' => $k->libro->id],
                                                [
                                                    'class' => 'btn btn-dark btn-block mt-auto card-text  text-light',
                                                    'style' => 'font-size: 8px;'
                                                ]
                                            ) ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endif ?>

                    <?php if (isset($showSeg)) : ?>
                        <br><br>
                        <div>
                            <h4>Tu cine y series: </h4>
                            <hr>
                            <div class="row">
                                <?php foreach ($showSeg as $k) : ?>
                                    <div class="card col-md-2 border-0">
                                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgCineUrl/' . $k->objetos->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'" alt="Card image cap">

                                        <div class="card-img-overlay card-body d-flex flex-column">
                                            <?= Html::a(
                                                $k->objetos->nombre,
                                                ['shows/view', 'id' => $k->objetos->id],
                                                [
                                                    'class' => 'btn btn-dark btn-block mt-auto card-text  text-light',
                                                    'style' => 'font-size: 8px;'
                                                ]
                                            ) ?>
                                        </div>
                                    </div>
                                <?php endforeach ?>
                            </div>
                        </div>
                    <?php endif ?>
                </div>
            <?php endif ?>

        </div>

    </div>