<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Listacapitulos */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="listacapitulos-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'genero_id')->dropdownList($generos) ?>
    <?= $form->field($model, 'objetos_id')->hiddenInput(['value' => $id])->label(false) ?>

    <div class="form-group">
        <?= Html::submitButton('Añadir', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Volver', ['/shows/view', 'id' => $id], ['class' => 'btn btn-warning']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>