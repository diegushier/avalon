<?php

use wbraganca\videojs\VideoJsWidget;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\Shows */
// https://www.youtube.com/watch?v=nWHUjuJ8zxE
$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' =>  'shows', 'url' => [$model->tipo . 's']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$back = "$('body').css('background-image', 'url( " . Yii::getAlias('@imgBackLibrosUrl/' . $model->id . '.jpg') . ")')
         $('#back').css('background-color', '#fff')
";

$this->registerJs($back);
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
                    <div class="modal-content" style="width: 840px; height :464px;">
                        <div class="modal-header">
                            <h5 class="modal-title" id="#vertrailerLongTitle"><?= $model->nombre ?></h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <?= VideoJsWidget::widget([
                                'options' => [
                                    'class' => 'video-js vjs-default-skin vjs-big-play-centered',
                                    'width' => '640',
                                    'height' => '264',
                                    'controls' => true,
                                ],
                                'jsOptions' => [
                                    'preload' => 'auto',
                                ],
                                'tags' => [
                                    'source' => [
                                        ['src' => 'http://vjs.zencdn.net/v/oceans.mp4', 'type' => 'video/mp4'],
                                        ['src' => 'http://vjs.zencdn.net/v/oceans.webm', 'type' => 'video/webm']
                                    ],
                                    'track' => [
                                        ['kind' => 'captions', 'src' => 'http://vjs.zencdn.net/vtt/captions.vtt', 'srclang' => 'en', 'label' => 'English']
                                    ]
                                ]
                            ]) ?>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($capitulos)) : ?>
                <button type="button" id="delete" class="btn btn-orange mb-2" data-toggle="modal" data-target="#capitulos">
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
                                        <?php $contador = 1; foreach ($capitulos as $fila) : ?>
                                            <tr>
                                                <td class="w-25"><?= $contador++ ?></td>
                                                <td class="w-25"><?= $fila->nombre ?></td>
                                                <td class="w-50"><?= $fila->sinopsis ?></td>
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
            <table class="table col-lg-5">
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
                        <td>Pais</td>
                        <td><?= $pais ?></td>
                    </tr>
                </tbody>
            </table>

            <h5>Sinopsis</h5>
            <p><?= $model->sinopsis ?></p>
        </div>
    </div>

    <?php if (!(Yii::$app->user->isGuest) && $duenio === Yii::$app->user->id) : ?>
        <div>
            <?= Html::a('Modificar ' . $model->nombre, ['update', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
            <button type="button" id="delete" class="btn btn-danger" data-toggle="modal" data-target="#borrarEmpresa">
                Eliminar <?= $model->nombre ?>
            </button>

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