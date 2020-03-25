<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ObjetosSearch */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="objetos-searchview">


    <?php $form = ActiveForm::begin([
        'action' => [$tipo],
        'method' => 'get',
    ]); ?>

    <div class="row">
        <div class="col-lg-10 col-sm-12">
        <?= $form->field($model, 'nombre')->textInput(['class' =>  'form-control', 'value' => '','placeholder' => 'Escriba el nombre del libro....'])->label(false) ?>
        </div>


        <div class="form-group col-lg-1">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>
</div>