<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Listacapitulos */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="listacapitulos-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'nombre')->textInput() ?>
    <?= $form->field($model, 'objetos_id')->hiddenInput(['value' => $id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>