<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Objetos */

$this->title = 'Modificar: ' . $model->nombre;
?>
<div class="objetos-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'paises' => $paises,
    ]) ?>

    <?= Html::a('Volver', ['view', 'id' => $model->id, 'tipo' => $tipo], ['class' => 'btn btn-warning']) ?>

</div>