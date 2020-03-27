<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Empresas */
?>
<div class="empresas-update">
    <?= $this->render('/empresas/_form', [
        'model' => $model,
        'pais' => $pais,
        'action' => $action
    ]) ?>

</div>