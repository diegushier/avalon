<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Libros */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => 'Libros', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$back = "$('body').css('background-image', 'url( " . Yii::getAlias('@imgBackLibrosUrl/' . $model->id . '.jpg') . ")')
         $('#back').css('background-color', '#fff')
";

$this->registerJs($back);
?>
<div class="libros-view">
    <div class="row">
        <div class="col-lg-3">
            <img src="<?= Yii::getAlias('@imgLibrosUrl/' . $model->id . '.jpg') ?>" class="m-3 p-1 w-100 shadow" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'">
        </div>
        <div class="views-container  mt-3 mb-3 col-lg-8">
            <table class="table col-lg-5">
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
                        <td>Genero</td>
                        <td><?= ucwords($genero) ?></td>
                    </tr>
                    <tr>
                        <td>ISBN</td>
                        <td><?= $model->isbn ?></td>
                    </tr>
                    <tr>
                        <td>Editorial</td>
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

            <?php if (file_exists(Yii::getAlias('@resumen/' . $model->id . '.txt'))) :?>
                <?= Html::a('Ver', ['resumen', 'id' => $model->id], ['class' => 'btn btn-orange', 'target' => '_blank']) ?>
            <?php endif ?>
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
                            <h5 class="modal-title" id="#borrarEmpresaLongTitle">Borrar Libro</h5>
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