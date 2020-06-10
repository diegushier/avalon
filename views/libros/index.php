<?php

use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LibrosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Libros';
?>
<div class="libros-index row">
    <div class="col-sm-12 col-lg-2 lg-border-right">

        <?php $form = ActiveForm::begin([
            'action' => ['libros/index'],
            'method' => 'get',
        ]); ?>

        <div class="form-group">
            <?= Html::textInput(
                'dataName',
                $dataName,
                ['class' =>  'form-control', 'value' => '', 'placeholder' => 'Palabra clave...', 'id' =>  'dataName']
            ) ?>
        </div>

        <?= Html::submitButton('Buscar', ['class' => 'mb-2 btn btn-orange w-100']) ?>

        <?php ActiveForm::end(); ?>
        <?php if (isset(Yii::$app->user->identity) && Yii::$app->user->identity->clave === null) : ?>
            <?= Html::a(
                'Añade tu libro',
                ['create', 'scenario' => true],
                [
                    'class' => 'btn btn-orange btn-block mb-2',
                ]
            ) ?>
        <?php endif ?>
        <ul class="list-group list-group-flush">
            <li class="list-group-item text-center font-weight-bold">Ordenar por:</li>
            <li class="list-group-item text-center"><?= $sort->link('nombre') ?></li>
            <li class="list-group-item text-center"><?= $sort->link('genero_id') ?></li>
        </ul>


    </div>
    <div class="col-sm-12 col-lg-9">
        <div class="row">
            <?php foreach ($libros as $libros) : ?>
                <div class="col-lg-3 col-sm-5 d-flex justify-content-center">
                    <div class="card mt-2" style="width: 15rem;">
                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgLibrosUrl/' . $libros->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.jpg') ?>'" alt="Card image cap">
                            <?= Html::a(
                                $libros->nombre,
                                ['libros/view', 'id' => $libros->id],
                                [
                                    'class' => 'btn btn-dark btn-block card-body d-flex flex-column mt-auto',
                                ]
                            ) ?>
                    </div>
                </div>
            <?php endforeach ?>
        </div>
    </div>
</div>