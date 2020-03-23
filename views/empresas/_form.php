<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Empresas */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="empresas-form border rounded p-3">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pais_id')->hiddenInput(['value' => $pais])->label(false) ?>

    <?= $form->field($model, 'entidad_id')->hiddenInput(['value' => $entidad])->label(false) ?>

    <div class="form-group offset-sm-2">
        <?= Html::submitButton('Crear empresa', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
