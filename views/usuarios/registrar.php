<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrarForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Sing up';
?>
<?php
$this->registerJs("jQuery('#reveal-password').change(() => { jQuery('#loginform-password').attr('type',this.checked?'text':'passwd');})");
?>
<div class="site-login">
    <h1 class="text-center"><?= Html::encode($this->title) ?></h1>
    <hr>

    <h3 class='text-center'>Introduzca los siguientes datos para registrarse:</h3>
    <hr>

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => ['wrapper' => 'col-sm-5'],
        ],
    ]); ?>

        <section>
            <h3>Nombre</h3>
            <?= $form->field($model, 'nickname')->textInput(['autofocus' => true]) ?>
            <small>*Este nombre es el que verán el resto de personas. Puede ser duplicado.</small>
            <br>
            <br>
            <?= $form->field($model, 'username')->textInput() ?>
            <small>*Este nombre es el que usarás para loguearte y hacer acciones de peligro. Es único.</small>
        </section>
        <hr>
        <section>
            <h3>Correo</h3>
            <?= $form->field($model, 'correo')->textInput() ?>  
            <?= $form->field($model, 'pais_id')->dropDownList($paises) ?>
        </section>
        <hr>
        <section>
            <h3>Contraseñas</h3>
            <?= $form->field($model, 'passwd')->passwordInput() ?>
            <?= $form->field($model, 'passwd_repeat')->passwordInput() ?>
        </section>

        <div class="form-group">
            <div class="offset-sm-2">
                <?= Html::submitButton('Registrar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                <?= Html::a('Login', ['/site/login'], ['class' => 'btn btn-primary']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>
</div>