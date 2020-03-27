<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Empresas */

\yii\web\YiiAsset::register($this);
?>
<div class="empresas-view p-3">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'nombre',
            [
                'attribute' => 'pais_id',
                'value' => $pais,
            ],
        ],
    ]) ?>

</div>
