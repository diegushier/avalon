<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Criticas */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="criticas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'libro_id')->hiddenInput(['value' => $objeto])->label(false) ?>
    
    <?= $form->field($model, 'usuario_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <?= $form->field($model, 'valoracion')->textInput([
        'type' => 'number'
    ]) ?>
    <?= $form->field($model, 'comentario')->textarea(['rows' => 6]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
