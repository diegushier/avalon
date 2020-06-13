<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrarForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Url;

$urlSend = Url::to(['usuarios/upclave']);
$urlUserAuth = Url::to(['usuarios/search']);
$urlPais = Url::to(['empresas/lista']);
$urlEmpSend = Url::to(['empresas/create']);
$user_id = Yii::$app->user->id;
$nombre = $model->nombre;

$js = <<<EOT
    var id = $user_id;
    var urlUserAuth = "$urlUserAuth";
    var urlSend = "$urlSend";
    var urlEmpSend = "$urlEmpSend";
    var urlPais = "$urlPais";

    setter(id, urlUserAuth, urlSend, urlEmpSend, urlPais)
    getUser(id, urlUserAuth)    
EOT;

$this->registerJsFile(
    '@web/js/modify.js',
    ['depends' => [\yii\web\JqueryAsset::className()]]
);
$this->registerJs($js);

$this->title = 'Modificar perfil';
?>
<div class="usuarios-modify">
    <h1 class='text-center'><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>
    <legend class='text-center'>Cambiar Datos</legend>
    <div class="row">
        <div class="col-lg-2 col-sm-12 w-100">
            <span class="btn btn-default btn-file">
                <img id="setImg" src="<?= Yii::getAlias('@imgUrl/plus.png') ?>" class="plus w-100" alt="">
                <?= $form->field($model, 'imagen')->fileInput(['class' => ''])->label(false) ?>
            </span>
        </div>
        <div class="col-lg-8 col-sm-12">

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
                    <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#borrarUsuario">
                        Eliminar
                    </button>
                    <?= Html::a('Volver', ['view'], ['class' => 'btn btn-orange']) ?>

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
                                    <?= Html::a('Eliminar', ['delete'], [
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
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

    <hr>
    <br><br><br>

    <div class="row">
        <div class="col-sm-12 col-lg-8" id="formEmp">
        </div>
    </div>


</div>