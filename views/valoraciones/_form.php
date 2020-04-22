<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Valoraciones */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="valoraciones-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'objetos_id')->textInput() ?>

    <?= $form->field($model, 'usuario_id')->textInput() ?>

    <?= $form->field($model, 'valoracion')->textInput() ?>

    <?= $form->field($model, 'comentario')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'fecha')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>