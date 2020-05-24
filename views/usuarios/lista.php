<?php

/* @var $this yii\web\View */

use Symfony\Component\VarDumper\VarDumper;
use yii\bootstrap4\Html;

$this->title = 'Mi lista';
$a = true;
$b = true;
$c = true;

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

    <div class="col-sm-12 col-lg-9 ">
        <?php if (isset($libros)) : ?>
            <div class="container mt-2">
                <h4 class="m-2">Tus libros......</h4>
                <?php if (count($libros) === 1) : ?>
                    <div class="card-deck row">
                        <div class="card col-4 border-0">
                            <?= Html::a(
                                '<img style="height: 250px" src="' . Yii::getAlias('@imgLibrosUrl/' . $libros[0]->id . '.jpg') . '" onerror="this.src = ' . Yii::getAlias('@imgUrl/notfound.png') . '">',
                                ['/libros/view', 'id' => $libros[0]->id],
                            ) ?>
                        </div>
                    </div>
                <?php elseif (count($libros) <= 4) : ?>
                    <div class="card-deck row rounded-0">
                        <?php foreach ($libros as $k) : ?>
                            <div class="card col-4 border-0">
                                <?= Html::a(
                                    '<img style="height: 250px" src="' . Yii::getAlias('@imgLibrosUrl/' . $k->id . '.jpg') . '" onerror="this.src = ' . Yii::getAlias('@imgUrl/notfound.png') . '">',
                                    ['/libros/view', 'id' => $k->id],
                                ) ?>
                            </div>
                        <?php endforeach ?>
                    </div>
                <?php else : ?>
                    <div id="carouselExampleControls" class="carousel slide w-100" style="height: 150px" data-ride="carousel">
                        <div class="carousel-inner bg-dark">
                            <?php foreach ($libros as $k) : ?>
                                <?php if ($a) : ?>
                                    <div class="carousel-item active">
                                        <?php $a = false ?>
                                    <?php else : ?>
                                        <div class="carousel-item ">
                                        <?php endif ?>
                                        <?= Html::a(
                                            '<img style="height: 250px" src="' . Yii::getAlias('@imgLibrosUrl/' . $k->id . '.jpg') . '" onerror="this.src = ' . Yii::getAlias('@imgUrl/notfound.png') . '" alt="Card image cap">',
                                            ['/libros/view', 'id' => $k->id],
                                        ) ?>

                                        </div>
                                    <?php endforeach ?>
                                    </div>
                        </div>
                        <a class="carousel-control-prev text-black-50" href="#carouselExampleControls" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                <?php endif ?>
            <?php endif ?>

            <?php if (isset($cine)) : ?>
                <div class="mt-2">

                    <h4>Tus pelis......</h4>
                    <?php if (count($cine) === 1) : ?>
                        <div class="card-deck row">
                            <div class="card col-4 border-0">
                                <?= Html::a(
                                    '<img style="height: 250px" src="' . Yii::getAlias('@imgLibrosUrl/' . $cine[0]->id . '.jpg') . '" onerror="this.src = ' . Yii::getAlias('@imgUrl/notfound.png') . '">',
                                    ['/shows/view', 'id' => $cine[0]->id],
                                ) ?>
                            </div>
                        </div>
                    <?php elseif (count($cine) <= 4) : ?>
                        <div class="card-deck row rounded-0">
                            <?php foreach ($cine as $k) : ?>
                                <div class="card col-4 border-0">
                                    <?= Html::a(
                                        '<img style="height: 250px" src="' . Yii::getAlias('@imgLibrosUrl/' . $k->id . '.jpg') . '" onerror="this.src = ' . Yii::getAlias('@imgUrl/notfound.png') . '">',
                                        ['/shows/view', 'id' => $k->id],
                                    ) ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php else : ?>
                        <div id="carouselExampleControls" class="carousel slide w-100" style="height: 150px" data-ride="carousel">
                            <div class="carousel-inner bg-dark">
                                <?php foreach ($cine as $k) : ?>
                                    <?php if ($a) : ?>
                                        <div class="carousel-item active">
                                            <?php $a = false ?>
                                        <?php else : ?>
                                            <div class="carousel-item ">
                                            <?php endif ?>
                                            <?= Html::a(
                                                '<img style="height: 250px" src="' . Yii::getAlias('@imgLibrosUrl/' . $k->id . '.jpg') . '" onerror="this.src = ' . Yii::getAlias('@imgUrl/notfound.png') . '" alt="Card image cap">',
                                                ['/cine/view', 'id' => $k->id],
                                            ) ?>

                                            </div>
                                        <?php endforeach ?>
                                        </div>
                            </div>
                            <a class="carousel-control-prev text-black-50" href="#carouselExampleControls" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    <?php endif ?>
                </div>
            <?php endif ?>


            <?php if (isset($serie)) : ?>
                <div class="mt-2">
                    <h4>Tus series......</h4>
                    <?php if (count($series) === 1) : ?>
                        <div class="card-deck row">
                            <div class="card col-4 border-0">
                                <?= Html::a(
                                    '<img style="height: 250px" src="' . Yii::getAlias('@imgLibrosUrl/' . $series[0]->id . '.jpg') . '" onerror="this.src = ' . Yii::getAlias('@imgUrl/notfound.png') . '">',
                                    ['/shows/view', 'id' => $series[0]->id],
                                ) ?>
                            </div>
                        </div>
                    <?php elseif (count($series) <= 4) : ?>
                        <div class="card-deck row rounded-0">
                            <?php foreach ($series as $k) : ?>
                                <div class="card col-4 border-0">
                                    <?= Html::a(
                                        '<img style="height: 250px" src="' . Yii::getAlias('@imgLibrosUrl/' . $k->id . '.jpg') . '" onerror="this.src = ' . Yii::getAlias('@imgUrl/notfound.png') . '">',
                                        ['/shows/view', 'id' => $k->id],
                                    ) ?>
                                </div>
                            <?php endforeach ?>
                        </div>
                    <?php else : ?>
                        <div id="carouselExampleControls" class="carousel slide w-100" style="height: 150px" data-ride="carousel">
                            <div class="carousel-inner bg-dark">
                                <?php foreach ($series as $k) : ?>
                                    <?php if ($a) : ?>
                                        <div class="carousel-item active">
                                            <?php $a = false ?>
                                        <?php else : ?>
                                            <div class="carousel-item ">
                                            <?php endif ?>
                                            <?= Html::a(
                                                '<img style="height: 250px" src="' . Yii::getAlias('@imgLibrosUrl/' . $k->id . '.jpg') . '" onerror="this.src = ' . Yii::getAlias('@imgUrl/notfound.png') . '" alt="Card image cap">',
                                                ['/series/view', 'id' => $k->id],
                                            ) ?>

                                            </div>
                                        <?php endforeach ?>
                                        </div>
                            </div>
                            <a class="carousel-control-prev text-black-50" href="#carouselExampleControls" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="sr-only">Previous</span>
                            </a>
                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="sr-only">Next</span>
                            </a>
                        </div>
                    <?php endif ?>

                </div>
            <?php endif ?>
            </div>