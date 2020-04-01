<?php

use yii\bootstrap4\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Listacapitulos */

$this->title = 'Create Listacapitulos';
$this->params['breadcrumbs'][] = ['label' => 'Listacapitulos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listacapitulos-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
        'id' => $id
    ]) ?>

</div>
