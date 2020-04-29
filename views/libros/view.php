<?php

use yii\bootstrap4\Html;
use yii\helpers\Json;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Libros */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Libros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$quest = !(Yii::$app->user->isGuest) && ($duenio === Yii::$app->user->id);
\yii\web\YiiAsset::register($this);


$back = "$('body').css('background-image', 'url( " . Yii::getAlias('@imgBackLibrosUrl/' . $model->id . '.jpg') . ")')
         $('#back').css('background-color', '#fff')
         $(function () {
            $('[data-toggle=" . 'popover' . "]').popover()
          })";


$this->registerJs($back);
$this->registerCssFile('@web/css/comentario.css');
?>
<div class="shows-view">
    <div class="row">
        <div class="col-lg-3">
            <img src="<?= Yii::getAlias('@imgLibrosUrl/' . $model->id . '.jpg') ?>" class="m-3 p-1 w-100 shadow" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'">
        </div>
        <div class="views-container mt-3 mb-3 col-lg-8">
            <button type="button" id="trailer" class="btn btn-orange mb-2 mr-1" data-toggle="modal" data-target="#vertrailer">
                Ver muestra
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
            <?php if ($quest) : ?>
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
                        <td>Autor</td>
                        <td><?= $autor ?></td>
                    </tr>
                    <tr>
                        <td>Editorial</td>
                        <td><?= $productora ?></td>
                    </tr>
                    <tr>
                        <td>Pais</td>
                        <td><?= $pais ?></td>
                    </tr>
                    <?php if ($media->suma !== 0) : ?>
                        <tr>
                            <td>Puntuación</td>
                            <td><?= $media->total / $media->suma ?> de 5</td>
                        </tr>
                    <?php endif ?>
                    <?php if ($model->fecha !== '' && $model->fecha !== null) : ?>
                        <tr>
                            <td>Fecha de estreno</td>
                            <td><?= date_format(date_create($model->fecha), 'j F Y') ?></td>
                        </tr>
                    <?php endif ?>
                    <tr>
                        <td>Generos</td>
                        <td>
                            <div class="btn-group dropright">
                                <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="<?= $genero->nombre ?>" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                    <?= $genero->nombre ?>
                                </a>

                                <div class="dropdown-menu p-0 ml-1 border-0" aria-labelledby="dropdownMenuLink">
                                    <?= Html::a('&#x2699', ['/listageneros/update', 'id' => $genero->id, 'serie' => $model->id], ['class' => 'btn btn-orange']) ?>
                                    <?= Html::a('X', ['/listageneros/delete', 'id' => $genero->id, 'serie' => $model->id], [
                                        'class' => 'btn btn-danger',
                                        'data' => [
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
            <hr>
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
                                    <div class="comment-avatar"><img src="<?= Yii::getAlias('@imgUserUrl/' . $k->usuario->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/no-user.jpg') ?>'"></div>
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
                                            <?php if (Yii::$app->user->id === $k->usuario_id) : ?>
                                                <i>
                                                    <?= Html::a(
                                                        '',
                                                        ['/criticas/delete', 'id' => $k->id, 'objeto' => $model->id],
                                                        ['class' => 'fa fa-times text-danger',  'data' => [
                                                            'method' => 'post',
                                                        ],]
                                                    ) ?>
                                                </i>
                                            <?php endif ?>
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

            </div>
        <?php endif ?>
        <?php if (isset(Yii::$app->user->identity) && !$comented) : ?>
            <div class="container">

                <?= $this->render(
                    '/criticas/create',
                    [
                        'model' => $val,
                        'objeto' => $model->id
                    ]
                ) ?>
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