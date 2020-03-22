<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrarForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$this->title = 'Modificar perfil';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1 class='text-center'><?= Html::encode($this->title) ?></h1>


    <?php $form = ActiveForm::begin([
        'id' => 'login-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'horizontalCssClasses' => ['wrapper' => 'col-sm-5'],
        ],
    ]); ?>

    <h2 id="usuarios">Datos de Usuario: </h2>

    <section>
        <h3>Nombre</h3>
        <?= $form->field($model, 'nickname')->textInput(['autofocus' => true, 'value' => Yii::$app->user->identity->nickname]) ?>
        <small style="color: #777">*Este nombre es el que verán el resto de personas. Puede ser duplicado.</small>
    </section>
    <hr>
    <section>
        <h3>Correo</h3>
        <?= $form->field($model, 'correo')->textInput() ?>
        <?= $form->field($model, 'pais_id')->dropDownList($paises) ?>
    </section>
    <hr>
    <section>
        <h3>Contraseñas</h3>
        <?= $form->field($model, 'passwd')->passwordInput() ?>
        <?= $form->field($model, 'passwd_repeat')->passwordInput() ?>
    </section>

    <hr>
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