<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ObjetosSearch */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="objetos-search ml-3">
    <p>
        <button class="btn btn-primary" type="button" data-toggle="collapse" data-target="#collapseSearch" aria-expanded="false" aria-controls="collapseSearch">
            Buscar <?= $tipo ?>
        </button>

        <div class="collapse" id="collapseSearch">
            <div class="card card-body">
                <?php $form = ActiveForm::begin([
                    'action' => [$tipo],
                    'method' => 'get',
                ]); ?>

                <?= $form->field($model, 'nombre') ?>


                <div class="form-group">
                    <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
                    <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </p>
</div>