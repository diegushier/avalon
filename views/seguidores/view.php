<?php

use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Seguidores */

$this->title = 'Seguidores';
\yii\web\YiiAsset::register($this);
?>
<div class="seguidores-view container">
    
    <h3>Seguidores:</h3>
    <?= Html::a(
        '<i class="fas fa-angle-left"></i> Volver',
        ['usuarios/view', 'id' => $id],
        ['class' => 'btn btn-dark']
    )?>
    <hr>
    <?php if (isset($model) && count($model) > 0) : ?>
        <ul class="list-group">
            <?php foreach ($model as $k) : ?>
                <li><?= $k->seguidor_id ?></li>
            <?php endforeach ?>
        </ul>
    <?php else : ?>
        <div class="alert alert-warning" role="alert">
            Lo sentimos, este usuario no tiene seguidores.
            <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endif ?>

</div>