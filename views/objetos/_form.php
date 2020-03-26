<?php

use kartik\date\DatePicker;
use kartik\datecontrol\DateControl;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model app\models\Objetos */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="objetos-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?php if ($model->tipo_id === 1) : ?>
        <?= $form->field($model, 'isbn')->textInput() ?>
    <?php endif ?>

    <?= $form->field($model, 'productora_id')->hiddenInput(['value' => $model->productora_id])->label(false) ?>

    <?= $form->field($model, 'tipo_id')->hiddenInput(['value' => $model->tipo_id])->label(false) ?>


    <?php echo $form->field($model, 'fecha')->widget(DateControl::class, [
                'type' => DateControl::FORMAT_DATE,
                'widgetOptions' => [
                    'pluginOptions' => [
                        'autoclose' => true,
                    ],
                ],
            ]) ?>

    <!-- <?= $form->field($model, 'fecha')->textInput() ?> -->

    <?= $form->field($model, 'pais_id')->dropDownList($paises) ?>


    <?= $form->field($model, 'sinopsis')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>