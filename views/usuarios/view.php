<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrarForm */

use yii\bootstrap4\Html as Bootstrap4Html;
use yii\helpers\Html;

$clave = $model->clave === null;
$this->title = $model->nickname;
?>
<div class="site-view row m-auto">
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
    <div class="row col-lg-8 col-sm-12">

        <div class="col-lg-4 col-sm-12">
            <img src="<?= Yii::getAlias('@imgUserUrl/' . $model->id . '.jpg') ?>" class="m-3 p-1 w-100 shadow" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'">
            <?= Html::a(
                '&#x2699',
                ['/usuarios/modify'],
                [
                    'class' => 'btn btn-orange m-3',
                ]
            ) ?>
            <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#borrarUsuario">
                Eliminar
            </button>
            <div class="modal fade" id="borrarUsuario" tabindex="-1" role="dialog" aria-labelledby="borrarUsuarioCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="borrarUsuarioLongTitle">Borrar cuenta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ¿Esta usted seguro de eliminar este usuario?
                            La acción será irreversible.

                            <div>
                                <input type="text" id='deleteUser' placeholder="Escriba su nombre para comfirmar" class="col-12 form-control mt-2">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <?= Html::a('Eliminar', ['/usuarios/delete'], [
                                'id' => 'comfirmDeleteUser',
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'method' => 'post',
                                    'params' => ['id' => Yii::$app->user->id],
                                ],
                            ]) ?>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-sm-12 mt-3">
            <table class="table">
                <h4>Usuario:</h4>

                <tbody>
                    <tr>
                        <td>Nickname</td>
                        <td><?= $model->nickname ?></td>
                    </tr>
                    <tr>
                        <td>Correo:</td>
                        <td><?= $model->correo ?></td>
                    </tr>
                    <tr>
                        <td>Pais:</td>
                        <td><?= $pais ?></td>
                    </tr>
                    <tr>
                        <td>Auntenticado</td>
                        <th><?= $clave ? Html::label('Si', null, ['style' => 'color: green']) : Html::label('No', null, ['style' => 'color:red']) ?></th>
                    </tr>
                </tbody>

                <?php if ($clave) : ?>
                    <table class="table">
                        <h4>Empresa:</h4>
                        <tbody>
                            <tr>
                                <td>Nombre</td>
                                <td><?= $empresa->nombre ?></td>
                            </tr>
                            <tr>
                                <td>Pais</td>
                                <td><?= $emp_pais ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif ?>
            </table>
        </div>
    </div>
</div>