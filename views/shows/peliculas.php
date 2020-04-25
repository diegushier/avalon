<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ShowsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Peliculas';
?>
<div class="shows-peliculas row">
    <?php echo $this->render('_search', ['model' => $searchModel, 'tipo' => 'peliculas']); ?>
    <div class="col-sm-12 col-lg-2 border-right">
        <p>
            <button class="btn btn-orange w-100" type="button" data-toggle="collapse" data-target="#menuSearch" aria-expanded="false" aria-controls="collapseExample">
                Menu
            </button>
            <?php if (isset(Yii::$app->user->identity) && Yii::$app->user->identity->clave === null) : ?>
                <?= Html::a(
                    'Añade tu pelicula',
                    ['create', 'tipo' => 'cine'],
                    [
                        'class' => 'btn btn-orange btn-block mt-1',
                    ]
                ) ?>
            <?php endif ?>
        </p>
        <div class="collapse" id="menuSearch">
            <ul class="list-group list-group-flush">
                <li class="list-group-item text-center font-weight-bold">Ordenar por:</li>
                <li class="list-group-item text-center"><?= $sort->link('nombre') ?></li>
                <li class="list-group-item text-center"><?= $sort->link('fecha') ?></li>
            </ul>
        </div>
    </div>
    <div class="col-sm-12 col-lg-9">
        <div class="row">
            <?php foreach ($peliculas as $peliculas) : ?>
                <div class="col-lg-3 col-sm-5 d-flex justify-content-center">
                    <div class="card mt-2" style="width: 15rem;">
                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgCineUrl/' . $peliculas->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'" alt="Card image cap">
                        <div class="card-body d-flex flex-column mt-auto">
                            <h5 class="card-title"><?= $peliculas->nombre ?></h5>
                            <?= Html::a(
                                'Ver',
                                ['shows/view', 'id' => $peliculas->id],
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