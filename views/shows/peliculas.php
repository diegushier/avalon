<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ShowsSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Peliculas';
?>
<div class="shows-peliculas row">
    <div class="col-sm-12 col-lg-2 lg-border-right">
        <?php $form = ActiveForm::begin([
            'action' => ['shows/peliculas'],
            'method' => 'get',
        ]); ?>

        <div class="form-group">
            <?= Html::textInput(
                'dataName',
                $dataName,
                ['class' =>  'form-control mb-2', 'value' => '', 'placeholder' => 'Palabra clave...', 'id' =>  'dataName']
            ) ?>
        </div>

        <?= Html::submitButton('Buscar', ['class' => 'btn btn-oranges w-100 mb-2']) ?>

        <?php ActiveForm::end(); ?>
        <div>
            <?php if (isset(Yii::$app->user->identity) && Yii::$app->user->identity->clave === null) : ?>
                <?= Html::a(
                    'Añade tu película',
                    ['create', 'scenario' => true],
                    [
                        'class' => 'btn btn-orange btn-block mb-2',
                    ]
                ) ?>
            <?php endif ?>

            <ul class="list-group list-group-flush">
                <li class="list-group-item text-center font-weight-bold">Ordenar por:</li>
                <li class="list-group-item text-center font-orange"><?= $sort->link('nombre') ?></li>
                <li class="list-group-item text-center font-orange"><?= $sort->link('productora') ?></li>
            </ul>

        </div>


    </div>
    <div class="col-sm-12 col-lg-9">
        <div class="row">
            <?php foreach ($shows as $shows) : ?>
                <div class="col-lg-3 col-sm-6 d-flex justify-content-center">
                    <div class="card mt-2" style="width: 15rem;">
                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgCineUrl/' . $shows->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'" alt="Card image cap">
                        <div class="card-body d-flex flex-column mt-auto">
                            <?= Html::a(
                                $shows->nombre,
                                ['shows/view', 'id' => $shows->id],
                                [
                                    'class' => 'btn btn-dark btn-block mt-auto',
                                ]
                            ) ?>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>