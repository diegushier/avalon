<?php

use kartik\sidenav\SideNav;
use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Objetos */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => ucwords($tipo), 'url' => ['objetos/' . lcfirst($tipo)]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$back = "$('body').css('background-image', 'url( " . Yii::getAlias('@imgBackUrl/' . $model->id . '.jpg') . ")')
         $('#back').css('background-color', '#fff')
";

$this->registerJs($back);
?>
<div class="objetos-view">
    <?php echo $this->render('_searchview', ['model' => $model, 'tipo' => $tipo]); ?>

    <div class="row">
        <img src="<?= Yii::getAlias('@imgUrl/' . $model->id . '.jpg') ?>" class="col-lg-3  m-3 p-0 shadow" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'">
        <div class="views-container mt-3 mb-3 col-lg-8">
            <table class="table col-lg-5">
                <tbody>
                    <tr>
                        <td>Nombre</td>
                        <td><?= $model->nombre ?></td>
                    </tr>

                    <tr>
                        <td>Autor</td>
                        <td><?= 'alguien' ?></td>
                    </tr>

                    <?php if ($tipo === 'libros') : ?>
                        <tr>
                            <td>ISBN</td>
                            <td><?= $model->isbn ?></td>
                        </tr>
                        <tr>
                            <td>Editorial</td>
                            <td><?= $productora ?></td>
                        </tr>
                    <?php else : ?>
                        <tr>
                            <td>Productora</td>
                            <td><?= $productora ?></td>
                        </tr>
                    <?php endif ?>

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

    <?php if ($duenio === Yii::$app->user->id) : ?>
        <div>
            <?= Html::a('Modificar ' . $model->nombre, ['update', 'id' => $model->id, 'tipo' => $tipo], ['class' => 'btn btn-success']) ?>
            <button type="button" id="delete" class="btn btn-danger" data-toggle="modal" data-target="#borrarEmpresa">
                Eliminar <?= $model->nombre ?>
            </button>

            <div class="modal fade" id="borrarEmpresa" tabindex="-1" role="dialog" aria-labelledby="#borrarEmpresaCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="#borrarEmpresaLongTitle">Borrar <?= substr($tipo, 0, -1) ?> </h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ¿Esta usted seguro de que desea ELIMINAR <?= $tipo === 'libros' ? 'este' :  'esta'?>  <?= substr($tipo, 0, -1) ?>?
                            La acción será irreversible. 
                            <?php if ($tipo === 'series') : ?>
                                Si la serie contiene capítulos, esta no podrá ser borrada.
                            <?php endif ?>
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