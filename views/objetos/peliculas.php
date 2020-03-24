<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ObjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Peliculas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objetos-index">
    <p>

        <?php Html::a('Create Objetos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php echo $this->render('_search', ['model' => $searchModel, 'tipo' => 'peliculas']); ?>

    <div class="container">
        <div class="row">
            <?php foreach ($peliculas as $peliculas) : ?>
                <div class="col-lg-3 col-sm-12 d-flex justify-content-center">
                    <div class="card mt-2" style="width: 15rem;">
                        <img class="card-img-top" src="" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'" alt="Card image cap">
                        <div class="card-body d-flex flex-column mt-auto">
                            <h5 class="card-title"><?= $peliculas['nombre'] ?></h5>
                            <?= Html::a(
                                'Ver',
                                ['objetos/view', 'id' => $peliculas['id'], 'tipo' => 'peliculas'],
                                [
                                    'class' => 'btn btn-primary btn-block mt-auto',
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>