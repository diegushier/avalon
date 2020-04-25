<?php

use lesha724\youtubewidget\Youtube;
use yii\bootstrap4\Html;
use yii\grid\GridView;
use yii\helpers\Json;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\Shows */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' =>  'shows', 'url' => [$model->tipo . 's']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$quest = !(Yii::$app->user->isGuest) && ($duenio === Yii::$app->user->id);


$back = "$('body').css('background-image', 'url( " . Yii::getAlias('@imgBackCineUrl/' . $model->id . '.jpg') . ")')
         $('#back').css('background-color', '#fff')
         $(function () {
            $('[data-toggle=" . 'popover' . "]').popover()
          })";


if ($quest && isset($ids)) {
    $modal = "ids = " . Json::encode($ids) . "
            $.each(ids, (k, v) => {
                $('#" . str_replace(' ', '_', $model->nombre) . "' + '-' +v['id']).click(() => {
                    var aux = $('#output').attr('href');
                    aux = aux.replace(/\&id\=\d+\&/, '&id='+v['id']+'&');
                    $('#output').attr('href', aux);
                });
            });
        ";
    $this->registerJs($modal);
}


$this->registerJs($back);
$this->registerCssFile('@web/css/comentario.css');

?>
<div class="shows-view">
    <div class="row">
        <div class="col-lg-3">
            <img src="<?= Yii::getAlias('@imgCineUrl/' . $model->id . '.jpg') ?>" class="m-3 p-1 w-100 shadow" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'">
        </div>
        <div class="views-container mt-3 mb-3 col-lg-8">
            <button type="button" id="trailer" class="btn btn-orange mb-2 mr-1" data-toggle="modal" data-target="#vertrailer">
                Ver Trailer
            </button>
            <div class="modal fade" id="vertrailer" tabindex="-1" role="dialog" aria-labelledby="#vertrailerCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content" style="width: 840px; height :500px;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="#vertrailerLongTitle"><?= $model->nombre ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($capitulos)) : ?>
                <button type="button" id="vercapitulos" class="btn btn-orange mb-2 mr-1" data-toggle="modal" data-target="#capitulos">
                    Ver capitulos
                </button>

                <div class="modal fade" id="capitulos" tabindex="-1" role="dialog" aria-labelledby="#capitulosCenterTitle" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="#capitulosLongTitle">Capitulos</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th>Capitulo</th>
                                            <th>Nombre</th>
                                            <th>Sinopsis</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $contador = 1;
                                        foreach ($capitulos as $fila) : ?>
                                            <tr>
                                                <td style="width: 10%"><?= $contador++ ?></td>
                                                <td style="width: 10%"><?= $fila->nombre ?></td>
                                                <td style="width: 60%"><?= $fila->sinopsis ?></td>
                                                <?php if ($quest) : ?>
                                                    <td style="width: 20%">
                                                        <button type="button" id="<?= str_replace(' ', '_', $model->nombre) . '-' . $fila->id ?>" class="btn btn-orange mb-2 mr-1" data-toggle="modal" data-target="#borrarCapitulo">X</button>
                                                        <?= Html::a('&#x2699', ['/capitulos/update', 'id' => $fila->id, 'modelid' => $model->id], [
                                                            'class' => 'btn btn-orange mb-2 mr-1',
                                                        ]) ?>
                                                    </td>
                                                <?php endif ?>
                                                <div class="modal fade" id="borrarCapitulo" tabindex="-1" role="dialog" aria-labelledby="#borrarCapituloCenterTitle" aria-hidden="true">
                                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title" id="borrarCapituloLongTitle"><?= $fila->nombre ?></h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                ¿Está seguro de borrar este capítulo?
                                                            </div>
                                                            <div class="modal-footer">
                                                                <?= Html::a('Si', ['/listacapitulos/delete', 'id' => $fila->id, 'serie' => $model->id], [
                                                                    'id' => 'output',
                                                                    'class' => 'btn btn-danger',
                                                                    'data' => [
                                                                        'method' => 'post',
                                                                    ],
                                                                ]) ?>
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </tr>
                                        <?php endforeach ?>
                                        <tr>
                                            <?= Html::a('Añadir capítulo', ['/listacapitulos/create', 'id' => $model->id], [
                                                'class' => 'btn btn-warning w-100',
                                            ]) ?>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif ?>
            <?php if ($quest) : ?>
                <?= Html::a('Añadir genero', ['/listageneros/create', 'id' => $model->id], ['class' => 'btn btn-success mb-2 mr-1']) ?>
                <?= Html::a('&#x2699', ['update', 'id' => $model->id], ['style' => 'font-size: 15px', 'class' => 'btn btn-success mb-2 mr-1']) ?>
                <button type="button" id="delete" class="btn btn-danger mb-2 mr-1" style="font-size: 20px" data-toggle="modal" data-target="#borrarEmpresa">
                    &times
                </button>
            <?php endif ?>

            <table class="table col-lg-8 col-sm-12">
                <tbody>
                    <tr>
                        <td>Nombre</td>
                        <td><?= $model->nombre ?></td>
                    </tr>
                    <tr>
                        <td>Productora</td>
                        <td><?= $productora ?></td>
                    </tr>
                    <tr>
                        <td>Puntuación</td>
                        <td><?= $media->total / $media->suma ?></td>
                    </tr>
                    <?php if ($model->fecha !== '' && $model->fecha !== null) : ?>
                        <tr>
                            <td>Fecha de estreno</td>
                            <td><?= date_format(date_create($model->fecha), 'j F Y') ?></td>
                        </tr>
                    <?php endif ?>
                    <tr>
                        <td>Pais</td>
                        <td><?= $pais ?></td>
                    </tr>
                    <tr>
                        <td>Generos</td>
                        <td>
                            <?php $contador = 0;
                            foreach ($generos as $g) : ?>
                                <div class="btn-group dropright mt-1">
                                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="<?= $contador++ . '-' . $g->nombre ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        <?= $g->nombre ?>
                                    </a>

                                    <div class="dropdown-menu p-0 ml-1" aria-labelledby="dropdownMenuLink">
                                        <?= Html::a('Modificar', ['/listageneros/update', 'id' => $g->id, 'serie' => $model->id], ['class' => 'btn btn-orange']) ?>
                                        <?= Html::a('Borrar', ['/listageneros/delete', 'id' => $g->id, 'serie' => $model->id], [
                                            'class' => 'btn btn-danger',
                                            'data' => [
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <h5>Sinopsis</h5>
            <p><?= $model->sinopsis ?></p>
        </div>
        <?php if ($criticas) : ?>
            <div class="col-12 m-2 ">
                <h5 class="font-weight-bold m-3">Comentarios</h5>
                <div class="btn-group dropright mt-1">
                    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Ordenar por:
                    </a>

                    <div class="dropdown-menu p-0 ml-1 border-0" aria-labelledby="dropdownMenuLink">
                        <?= $sort->link('fecha', ['class' => 'btn btn-orange']) ?>
                    </div>
                </div>
                <div class="row m-3">
                    <ul id="comments-list" class="comments-list">
                        <?php foreach ($criticas as $k) : ?>
                            <li>
                                <div class="comment-main-level">
                                    <!-- Avatar -->
                                    <div class="comment-avatar"><img src="<?= Yii::getAlias('@imgUserUrl/' . $k->usuario->id . '.jpg') ?>" alt=""></div>
                                    <!-- Contenedor del Comentario -->
                                    <div class="comment-box">
                                        <div class="comment-head">
                                            <h6 class="comment-name"><?= $k->usuario->nickname ?></h6>
                                            <div class="d-flex flex-row-reverse">
                                                <span>
                                                    <?php $now = date_format(date_create($k->fecha), 'H:i');
                                                    $date = date_format(date_create($k->fecha), 'd/m/Y');
                                                    if (date_format(new DateTime(), 'd/m/Y') === $date) : ?>
                                                        <?= $now ?>
                                                    <?php else : ?>
                                                        <?= $date ?>
                                                    <?php endif ?>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="comment-content">
                                            <?= $k->comentario ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                        <?php endforeach ?>


                    </ul>
                </div>
                <div>
                    <?= $this->render(
                        '/valoraciones/create',
                        [
                            'model' => $val,
                            'objeto' => $model->id
                        ]
                    ) ?>
                </div>
            </div>
        <?php endif ?>
    </div>

    <?php if ($quest) : ?>
        <div>
            <div class="modal fade" id="borrarEmpresa" tabindex="-1" role="dialog" aria-labelledby="#borrarEmpresaCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="#borrarEmpresaLongTitle">Borrar</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ¿Esta usted seguro de que desea eliminarlo?
                            La acción será irreversible.
                        </div>
                        <div class="modal-footer">
                            <?= Html::a('Borrar ' . $model->nombre, ['delete', 'id' => $model->id], [
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'method' => 'post',
                                ],
                            ]) ?>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif ?>

</div>

</div>