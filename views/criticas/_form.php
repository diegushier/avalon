<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Criticas */
/* @var $form yii\bootstrap4\ActiveForm */

$values = [1, 2, 3, 4, 5];
?>

<div class="criticas-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'libro_id')->hiddenInput(['value' => $objeto])->label(false) ?>

    <?= $form->field($model, 'usuario_id')->hiddenInput(['value' => Yii::$app->user->id])->label(false) ?>

    <?= $form->field($model, 'valoracion')->dropDownList($values) ?>

    <?= $form->field($model, 'comentario')->textarea(['rows' => 6, 'style' => 'resize: none;']) ?>
</div>


<div class="form-group">
    <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
</div>

<?php ActiveForm::end(); ?>

</div>