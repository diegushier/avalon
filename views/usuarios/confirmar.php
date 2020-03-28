<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrarForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

?>
<div class="usuarios-comfirmar">

    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => ['wrapper' => 'col-sm-5'],
        ],
    ]); ?>

    <?= $form->field($model, 'clave')->textInput(['autofocus' => true, 'value' => '']) ?>

    <div class="form-group">
        <div class="offset-sm-2">
            <?= Html::submitButton('Comfirmar', ['class' => 'btn btn-primary']) ?>
        </div>
    </div>

    <?php ActiveForm::end(); ?>
</div>