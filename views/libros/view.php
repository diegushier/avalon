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

                                    <div class="dropdown-menu p-0 ml-1" aria-labelledby="dropdownMenuLink">
                                        <?= Html::a('Modificar', ['/listageneros/update', 'id' => $genero->id, 'serie' => $model->id], ['class' => 'btn btn-orange']) ?>
                                        <?= Html::a('Borrar', ['/listageneros/delete', 'id' => $genero->id, 'serie' => $model->id], [
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

            <h5>Sinopsis</h5>
            <p><?= $model->sinopsis ?></p>
        </div>
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