<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$img = Yii::getAlias('@imgUrl/login.jpg');

$js = "$('body').css({
            backgroundImage : 'url(" . $img . ")'
        ,   backgroundColor : 'black'  
        });
    ";

$this->registerJs($js);
$this->title = 'Recuperar';
?>
<div class="usuarios-recuperar">

    <div class="container bg-white w-sm w-lg w-xl rounded shadow-sm">
        <div class="mb-3 p-1">
            <h1 class="titles text-center border-bottom-1 pt-3 mb-3"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="forms m-2 p-3" style="height: 350px;">
            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'horizontalCssClasses' => ['wrapper' => 'col-sm-5'],
                ],
            ]); ?>

            <?= $form->field($model, 'passwd')->passwordInput(['placeholder' => 'Nueva Contraseña', 'value' => '', 'class' => 'inputs col-12'])->label(false) ?>
            <?= $form->field($model, 'passwd_repeat')->passwordInput(['placeholder' => 'Repita la contraseña', 'value' => '', 'class' => 'inputs col-12'])->label(false) ?>

            <p>Por favor, ingrese su correo para poder verificar que usted tiene cuenta.</p>
            <hr>
            <div class="form-group">
                <div class="offset-sm-2">
                    <?= Html::submitButton('Cambiar', ['class' => 'btn btn-orange col-12 mx-auto', 'data-toggle' => 'modal', 'data-target' => '#loading', 'name' => 'login-button']) ?>
                </div>
            </div>

            <div class="modal fade" id="loading" tabindex="-1" role="dialog" aria-labelledby="loadingModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title text-center" id="loadingModalLabel">Cargando....</h5>
                        </div>
                        <div class="modal-body row" style="margin: auto;">
                            <div class="lds-dual-ring col-12"></div>
                        </div>
                    </div>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>