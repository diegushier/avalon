<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrarForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\DetailView;

$this->title = 'Modificar perfil';
?>
<div class="site-login">
    <h1 class='text-center'><?= Html::encode($this->title) ?></h1>

    <div class="row">
        <div class="col px-md-5">

            <?php $form = ActiveForm::begin([
                'id' => 'login-form',
                'layout' => 'horizontal',
                'fieldConfig' => [
                    'horizontalCssClasses' => ['wrapper' => 'col-sm-5'],
                ],
            ]); ?>

            <legend class='text-center'>Cambiar Datos</legend>

            <section class="border rounded p-3">
                <h5>Nombre</h5>
                <?= $form->field($model, 'nickname')->textInput(['autofocus' => true, 'value' => Yii::$app->user->identity->nickname]) ?>
                <small style="color: #777">*Este nombre es el que verán el resto de personas. Puede ser duplicado.</small>
            </section>
            <br>

            <section class="border rounded p-3">
                <h5>Correo</h5>
                <?= $form->field($model, 'correo')->textInput() ?>
                <?= $form->field($model, 'pais_id')->dropDownList($paises) ?>
            </section>
            <br>

            <section class="border rounded p-3">
                <h5>Contraseñas</h5>
                <?= $form->field($model, 'passwd')->passwordInput() ?>
                <?= $form->field($model, 'passwd_repeat')->passwordInput() ?>
            </section>
            <br>

            <div class="form-group">
                <div class="offset-sm-2">
                    <?= Html::submitButton('Modificar', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                    <?= Html::a('Eliminar', ['delete', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => '¿Esta seguro de eliminar este usuario? La acción será irreversible.',
                            'method' => 'post',
                        ],
                    ]) ?>
                </div>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <hr>
    <br><br><br>

    <?php if ($exists == false) : ?>
        <div>
            <section>
                <h2>Empresa</h2>
                <p>Para mostrar sus ppropios libros, peliculas o series deberá tener creada una entidad. Desde aqúi puede hacerlo.</p>
                <?= Html::a('Crear Empresa', ['empresas/create'], ['class' => 'btn btn-success']) ?>
            </section>
        </div>
    <?php else : ?>
        <div>
            <section>
                <h2>Empresa</h2>

                <?= Html::a('Eliminar ' . $empresa[0]['nombre'], ['empresas/delete'], [
                    'class' => 'btn btn-danger',
                    'data' => [
                        'confirm' => '¿Esta seguro de eliminar esta empresa? La acción será irreversible.',
                        'method' => 'post',
                    ],
                ]) ?>
            </section>
        </div>
    <?php endif ?>
</div>