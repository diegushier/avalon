<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Empresas */

?>
<div class="empresas-create">
    <?= $this->render('/empresas/_form', [
        'model' => $model,
        'paises' => $paises,
        'entidad' => $entidad,
        'action' => $action
    ]) ?>

</div>