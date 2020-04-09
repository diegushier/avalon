<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LibrosSearch */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="libros-search col-12">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>
    <div class="row">
        <div class="col-lg-10 col-sm-12">
            <?= $form->field($model, 'nombre')->textInput(['class' =>  'form-control', 'value' => '', 'placeholder' => 'Escriba el nombre....'])->label(false) ?>
        </div>


        <div class="form-group col-sm-12 col-lg-1">
            <?= Html::submitButton('Buscar', ['class' => 'btn btn-orange w-100']) ?>
        </div>

    </div>

    <?php ActiveForm::end(); ?>

</div>