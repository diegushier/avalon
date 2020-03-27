<?php

use yii\bootstrap4\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Empresas */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="empresas-form border rounded p-3">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'pais_id')->hiddenInput(['value' =>  $pais])->label(false) ?>

    <?= $form->field($model, 'entidad_id')->hiddenInput(['value' => ( $action == 'crear' ? $entidad : $model->entidad_id )])->label(false) ?>

    <?php if ($action == 'crear') : ?>

        <div class="form-group offset-sm-2">
            <?= Html::submitButton($action . ' empresa', ['class' =>  'btn btn-primary', 'data' => ['method' => 'post']]) ?>
        </div>

    <?php else : ?>

        <button type="button" class="btn btn-danger" data-toggle="modal" data-target="#modEmpresa">
            Modificar
        </button>

        <div class="modal fade" id="modEmpresa" tabindex="-1" role="dialog" aria-labelledby="#modEmpresaCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="#modEmpresaLongTitle">Cambiar nombre</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ¿Esta usted seguro de que desea cambiar el nombre de la empresa?
                    </div>
                    <div class="modal-footer">
                        <div class="form-group offset-sm-2">
                            <?= Html::submitButton($action . ' empresa', ['class' => 'btn btn-warning', 'data' => ['method' => 'post']]) ?>
                        </div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
        </div>

    <?php endif ?>

    <?php ActiveForm::end(); ?>


</div>