<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ObjetosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Series';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="objetos-index">

    <?php echo $this->render('_search', ['model' => $searchModel, 'tipo' => 'series']); ?>

    <div class="container">
        <div class="row">
            <?php foreach ($series as $series) : ?>
                <div class="col-lg-3 col-sm-5 d-flex justify-content-center">
                    <div class="card mt-2" style="width: 15rem;">
                        <img class="card-img-top mw-100 mh-100" src="<?= Yii::getAlias('@imgUrl/' . $series['id'] . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'" alt="Card image cap">
                        <div class="card-body d-flex flex-column mt-auto">
                            <h5 class="card-title"><?= $series['nombre'] ?></h5>
                            <?= Html::a(
                                'Ver',
                                ['objetos/view', 'id' => $series['id'], 'tipo' => 'series'],
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