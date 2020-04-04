<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Empresas */
?>
<div class="empresas-update">
    <?= $this->render('_form', [
        'model' => $model,
        'generos' => $generos,
        'id' => $id
    ]) ?>

</div>