<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Objetos */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="objetos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'isbn')->textInput() ?>

    <?= $form->field($model, 'productora_id')->textInput() ?>

    <?= $form->field($model, 'tipo_id')->textInput() ?>

    <?= $form->field($model, 'pais_id')->textInput() ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <?= $form->field($model, 'sinopsis')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
