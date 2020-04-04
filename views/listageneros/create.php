<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Listacapitulos */

$this->title = 'Añadir genero.';
?>
<div class="listacapitulos-create">
    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id,
        'generos' => $generos
    ]) ?>
</div>