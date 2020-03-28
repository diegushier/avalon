<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrarForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\DetailView;

$js = "
    var aux = true;
    $('#mod-can').click(() => {
        aux = !aux
        aux ? $('#mod-can').html('MODIFICAR EMPRESA') : $('#mod-can').html('CANCELAR');
        console.log('hola')
    })
";

$this->registerJs($js);

$this->title = 'Modificar perfil';
?>
<div class="site-login">
    <h1 class='text-center'><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col-lg-12 px-md-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'horizontalCssClasses' => ['wrapper' => 'col-sm-5'],
                ],
            ]); ?>

            <legend class='text-center'>Cambiar Datos</legend>

            <section class="border rounded p-3">
                <h5>Nombre</h5>
                <?= $form->field($model, 'nickname')->textInput(['autofocus' => true, 'value' => Yii::$app->user->identity->nickname]) ?>
                <small style="color: #777">*Este nombre es el que verán el resto de personas. Puede ser duplicado.</small>
            </section>
            <br>

            <section class="border rounded p-3">
                <h5>Correo</h5>
                <?= $form->field($model, 'correo')->textInput() ?>
                <?= $form->field($model, 'pais_id')->dropDownList($paises) ?>
            </section>
            <br>

            <section class="border rounded p-3">
                <h5>Contraseñas</h5>
                <?= $form->field($model, 'passwd')->passwordInput() ?>
                <?= $form->field($model, 'passwd_repeat')->passwordInput() ?>
            </section>
            <br>

            <div class="form-group">
                <div class="offset-sm-2">
                    <?= Html::submitButton('Modificar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>


                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#borrarUsuario">
                        Eliminar
                    </button>

                    <!-- Modal -->
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
                                    La acción será irreversible
                                </div>
                                <div class="modal-footer">
                                    <?php if ($exists) : ?>
                                        <?= Html::a('Eliminar', ['delete', 'id' => $model->id, 'entidad' => $get[0]['id']], [
                                            'class' => 'btn btn-danger',
                                            'data' => [
                                                'method' => 'post',
                                            ],
                                        ]) ?>

                                    <?php else : ?>
                                        <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                                            'class' => 'btn btn-danger',
                                            'data' => [
                                                'method' => 'post',
                                            ],
                                        ]) ?>
                                    <?php endif ?>

                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>

        <hr>
        <br><br><br>


        <?php if ($model->clave !== null) : ?>
            <div class="col-lg-12 px-md-5">
                <section>
                    <h2>Confirmación de usuarios</h2>
                    <p>Para tener acceso a la creación de una empresa, primero debe confirmar su cuenta mediante la clave que le fué enviada a su correo.</p>
                    <p>Esto no es más que una medida de seguridad.</p>

                    <?= $this->render('confirmar', [
                            'model' => $model,
                    ]) ?>
                </section>
            </div>
        <?php else : ?>
            <?php if ($exists == false) : ?>
                <div class="col-lg-12 px-md-5">
                    <section class="">
                        <h2 class="pl-3 pt-4">Empresa</h2>
                        <p>Para mostrar sus propios libros, peliculas o series deberá tener creada una entidad. Desde aqúi puede hacerlo.</p>
                        <?= $this->render('/empresas/create', [
                            'model' => $empresa,
                            'pais' => Yii::$app->user->identity->pais_id,
                            'entidad' => Yii::$app->user->identity->id,
                            'action' => 'crear'
                        ]) ?>

                    </section>
                </div>
            <?php else : ?>
                <div class="col-lg-12 px-md-5">
                    <section>
                        <div class="border rounded">
                            <?=
                                $this->render('/empresas/view', [
                                    'id' => $get[0]['id'],
                                    'model' => $empresaModel,
                                    'pais' => $paises[Yii::$app->user->identity->pais_id],
                                ]);
                            ?>
                        </div>

                        <div class="collapse mt-2" id="mod">
                            <?= $this->render('/empresas/update', [
                                'model' => $empresa,
                                'pais' => Yii::$app->user->identity->pais_id,
                                'action' => 'modificar'
                            ]) ?>
                        </div>

                        <div class="modal fade" id="borrarEmpresa" tabindex="-1" role="dialog" aria-labelledby="#borrarEmpresaCenterTitle" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="#borrarEmpresaLongTitle">Borrar // Desvincular empresa.</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        ¿Esta usted seguro de que desea desvicular esta empresa?
                                        La acción será irreversible y no podrá volver a vincularse a ella.
                                        <br>
                                        Solo en caso de que no tenga ninguna relación con series libros o películas se borrará.
                                        <br>
                                    </div>
                                    <div class="modal-footer">
                                        <?= Html::a('Desvincular ' . $get[0]['nombre'], ['empresas/delete', 'id' => $get[0]['id']], [
                                            'class' => 'btn btn-danger',
                                            'id' => 'liberar',
                                            'data' => [
                                                'method' => 'post',
                                            ],
                                        ]) ?>

                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>

                    <section class="mt-3 offset-sm-2">
                        <button class="btn btn-warning" type="button" id="mod-can" data-toggle="collapse" data-target="#mod" aria-expanded="false" aria-controls="collapseExample">
                            Modificar empresa
                        </button>
                        <button type="button" id="delete" class="btn btn-danger" data-toggle="modal" data-target="#borrarEmpresa">
                            Eliminar <?= $get[0]['nombre'] ?>
                        </button>
                    </section>

                </div>
            <?php endif ?>
        <?php endif ?>
    </div>

</div>