<?php

use kartik\datecontrol\DateControl;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Objetos */

$js = "

if ($('#objetos-tipo_id').children('option:selected').val() !== '3') {
    $('.field-objetos-isbn').hide();     
}

$('#objetos-tipo_id').change(() => {
    var aux = $('#objetos-tipo_id').children('option:selected').val();
    if (aux === '3') {
        $('.field-objetos-isbn').show();
    } else {
        $('.field-objetos-isbn').hide();     
    }
})";

$this->registerJs($js);
$this->title = 'Generar proyecto';
?>
<div class="objetos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'productora_id')->hiddenInput(['value' => $empresa->id])->label(false) ?>

    <?= $form->field($model, 'tipo_id')->dropDownList($tipo) ?>

    <?= $form->field($model, 'isbn')->textInput() ?>

    <?php echo $form->field($model, 'fecha')->widget(DateControl::class, [
        'type' => DateControl::FORMAT_DATE,
        'widgetOptions' => [
            'pluginOptions' => [
                'autoclose' => true,
            ],
        ],
    ]) ?>

    <?= $form->field($model, 'pais_id')->dropDownList($paises) ?>


    <?= $form->field($model, 'sinopsis')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>