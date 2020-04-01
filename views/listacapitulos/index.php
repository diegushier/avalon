<?php

use yii\bootstrap4\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ListacapitulosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Listacapitulos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="listacapitulos-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Listacapitulos', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'id',
            'capitulo_id',
            'objetos_id',
            'temporada',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
