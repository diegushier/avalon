<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Libros */

$this->title = 'Crear nuevo libro';
?>
<div class="libros-create">

    <h1><?= Html::encode($this->title) ?></h1>



    <?= $this->render('_form', [
        'model' => $model,
        'imagen' => $imagen,
        'editorial' => $editorial,
        'paises' => $paises,
        'genero' => $genero,
        'autor' => $autor,
        'scenario' => $scenario,
    ]) ?>

</div>