<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

// $img = Yii::getAlias('@imgUrl/login.jpg');

// $js = "$('body').css({
//             backgroundImage : 'url(" . $img . ")'
//         ,   backgroundColor : 'black'  
//         });
//     ";

// $this->registerJs($js);
$this->title = 'Login';
?>
<div class="site-login">
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
            <?= $form->field($model, 'username')->textInput(['placeholder' => 'Usuario', 'value' => '', 'class' => 'inputs mb-3'])->label(false) ?>

            <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Contraseña', 'value' => '', 'class' => 'inputs'])->label(false) ?>

            <?= $form->field($model, 'rememberMe')->checkbox() ?>            
            <hr>
            <div class="form-group">
                <div class="offset-sm-2">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-orange col-4', 'data-toggle' => 'modal', 'data-target' => '#loading', 'name' => 'login-button']) ?>

                    <?= Html::a('Registrarse', ['usuarios/registrar'], ['class' => 'btn btn-orange col-6', 'name' => 'login-button']) ?>
                </div>
            </div>


            <?php echo  Html::a('He olvidado mi contraseña', ['usuarios/recuperar'], ['class' => 'btn btn-orange col-12', 'name' => 'login-button']) ?>


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