<?php

use kartik\datecontrol\DateControl;
use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\Json;

/* @var $this yii\web\View */
/* @var $model app\models\Libros */
/* @var $form yii\bootstrap4\ActiveForm */

$js = "arr = { '#imageform-imagen': '#setImg'}
$.each(arr, (k, v) => {
    $(k).change(function () {
        var input = this;
        var url = $(this).val();
        var ext = url.substring(url.lastIndexOf('.') + 1).toLowerCase();
        if (input.files && input.files[0] && (ext == 'gif' || ext == 'png' || ext == 'jpeg' || ext == 'jpg')) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(v).attr('src', e.target.result);
            }
            reader.readAsDataURL(input.files[0]);
        }
        else {
            $(v).attr('src', '/assets/no_preview.png');
        }
    });
})";

$this->registerJs($js);
?>

<div class="libros-form">

    <?php $form = ActiveForm::begin(); ?>
    <?php if (isset($imagen)) : ?>
        <span class="btn btn-default btn-file">
            <img id="setImg" src="<?= Yii::getAlias('@imgUrl/plus.png')?>" class="plus" alt="">
            <?= $form->field($imagen, 'imagen')->fileInput(['class' => ''])->label(false) ?>
        </span>
    <?php endif ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'editorial_id')->hiddenInput(['value' => $editorial])->label(false) ?>

    <?= $form->field($model, 'autor_id')->dropDownList($autor) ?>

    <?= $form->field($model, 'genero_id')->dropDownList($genero) ?>

    <?= $form->field($model, 'pais_id')->dropDownList($paises) ?>

    <?php echo $form->field($model, 'fecha')->widget(DateControl::class, [
        'type' => DateControl::FORMAT_DATE,
        'widgetOptions' => [
            'pluginOptions' => [
                'autoclose' => true,
            ],
        ],
    ]) ?>

    <?= $form->field($model, 'sinopsis')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Crear', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>