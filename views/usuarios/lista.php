<?php

/* @var $this yii\web\View */

use yii\bootstrap4\Html;

$this->title = 'Mi lista';

?>

<div class="site-index row m-auto">
    <div class="col-sm-12 col-lg-2 border-right">
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

    <?php Yii::debug($data) ?>
    <div class="col-sm-12 col-lg-9">
        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
            <?php foreach ($data as $k) : ?>
                <div class="carousel-inner mt-2">
                    <?php foreach ($k as $v) : ?>
                        <div class="carousel-item">
                            
                            <img src="<?= Yii::getAlias('@imgCineUrl/' . $v->id . '.jpg') ?>" class="d-block w-100" alt="Un ejemplo">
                        </div>
                    <?php endforeach ?>
                </div>
                <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </a>
            <?php endforeach ?>
        </div>
    </div>