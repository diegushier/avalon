<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Empresas */
?>
<div class="empresas-update">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'sinopsis')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Modificar', ['class' => 'btn btn-success']) ?>
        <?= Html::a('Cancelar', ['/shows/view', 'id' => $modelid], [
            'class' => 'btn btn-orange',
        ]) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>