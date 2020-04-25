<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Criticas */

?>
<div class="criticas-create container">

    <h5 class="font-weight-bold">Deja tu comentario.</h5>

    <?= $this->render('_form', [
        'model' => $model,
        'objeto' => $objeto,

    ]) ?>

</div>