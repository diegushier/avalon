<?php

use yii\bootstrap4\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Seguidores */

$this->title = 'Siguiendo';
\yii\web\YiiAsset::register($this);

$urlGet = Url::to(['seguidores/checker']);
$urlSet = Url::to(['seguidores/follow']);
$user_id = Yii::$app->user->identity->id;

$js = <<<EOT
function check(){
    $.ajax({
        method: 'GET',
        url: "$urlGet",
        data: {
            id: "$('#follow').val('user-val)",
            seguidor: "$user_id",
        },
        success:function(data) {
            if (data) {
                $('#follow').html('No seguir');
            } elseif (data === null) {
                $('#follow').remove();
            } else {
                $('#follow').html('Seguir');
            }
    
        },  
    })
}

$('#follow').click(() => {
    $.ajax({
        method: 'GET',
        url: "$urlSet",
        data: {
            id: "$id",
            user_id : "$user_id"
        },
        success:function() {
            check()
        },  
    })
})

check()
EOT;

?>
<div class="seguidores-siguiendo container">

    <h3>Siguiendo:</h3>
    <?= Html::a(
        '<i class="fas fa-angle-left"></i> Volver',
        ['usuarios/view', 'id' => $id],
        ['class' => 'btn btn-dark']
    ) ?>
    <hr>
    <?php if (isset($model) && count($model) > 0) : ?>
        <ul class="list-group">
            <?php foreach ($model as $k) : ?>
                <li class="list-group-item">           
                    <?= Html::a(
                        $k->user->nickname,
                        ['usuarios/view', 'id' => $k->user->id],
                        ['class' => 'btn btn-light']
                    ) ?>

                    <button id="follow" class="btn btn-light float-right"></button>
                </li>
            <?php endforeach ?>
        </ul>

        <?= Yii::debug($model) ?> 
    <?php else : ?>
        <div class="alert alert-warning" role="alert">
            Lo sentimos, este usuario no tiene seguidores.
            <button type="button" class="close" data-dismiss="alert" aria-label="close"><span aria-hidden="true">&times;</span></button>
        </div>
    <?php endif ?>

</div>