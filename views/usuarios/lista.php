<?php

/* @var $this yii\web\View */

use Symfony\Component\VarDumper\VarDumper;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = 'Mi lista';
$a = true;
$b = true;
$c = true;

$librosJS = Json::encode($libro);
$showsJS = Json::encode($show);

$js = <<<EOT

var libros = $librosJS;
var shows = $showsJS;
var librosId = [];
var showsId = [];

contador = 1990;
date = new Date();
date = date.getFullYear();

while(contador <= date) {
    $('#age').append('<option value="'+contador+'">'+contador+'</option>')
    contador++
}


shows.forEach(k => {
    var index = $('#cine_' + k.objetos_id)
    var nombre = index.text();
    if (nombre.length >= 9) {
        index.text(nombre.substr(0, 9) + '...')
        index.mouseover(() => {
            index.text(nombre)
        })
        
        index.mouseout(() => {
            index.text(nombre.substr(0, 9) + '...')
        });
    }
})

libros.forEach(k => {
    var index = $('#libro_' + k.libro_id)
    var nombre = index.text();
    if (nombre.length >= 9) {
        index.text(nombre.substr(0, 9) + '...')
        index.mouseover(() => {
            index.text(nombre)
        })

        index.mouseout(() => {
            index.text(nombre.substr(0, 9) + '...')
        });
    }
})

EOT;


$css = '
    .alert-own {
        color: #856404;
        background-color: #fff3cd;
        border-color: #ffeeba;
    } 
';


$this->registerJs($js);
$this->registerCss($css);

?>

<div class="site-index row m-auto">
    <div class="col-sm-12 col-lg-2 lg-border-right">

        <?= Html::a(
            'Calendario',
            ['/site/index'],
            [
                'class' => 'btn btn-orange w-100',
            ]
        ) ?>
        <?= Html::a(
            'Perfil',
            ['/usuarios/view'],
            [
                'class' => 'btn btn-orange w-100 mt-1 mb-2',
            ]
        ) ?>
        <br>
        <br>
        <br>

        <?php $form = ActiveForm::begin([
            'action' => ['usuarios/lista'],
            'method' => 'get',
        ]); ?>
        <div class="form-group">
            <?= Html::textInput(
                'dataName',
                $dataName,
                ['class' =>  'form-control', 'value' => '', 'placeholder' => 'Palabra clave...', 'id' =>  'dataName']
            ) ?>

            <?= Html::dropDownList(
                'age',
                $age,
                ['Año'],
                ['id' => 'age', 'class' => 'form-control mt-2']
            ) ?>
        </div>


        <?= Html::submitButton('Buscar', ['class' => 'btn btn-primary w-100']) ?>

        <?php ActiveForm::end(); ?>

    </div>

    <div class="col-sm-12 col-lg-8 ">
        <?php if (!(isset($libro) || isset($show))) : ?>
            <div class="alert alert-own">
                No estas siguiendo nada ni has visto nada aun...
            </div>
        <?php endif ?>

        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="vistos" role="tabpanel" aria-labelledby="list-1-list">
                <?php if (isset($libro)) : ?>
                    <div>
                        <h4>Tus libros: </h4>
                        <hr>
                        <div class="row">
                            <?php foreach ($libro as $k) : ?>
                                <div class="card col-md-2 border-0">
                                    <img class="card-img-top mw-100 mh-100" style="max-height: 100%" src="<?= Yii::getAlias('@imgLibrosUrl/' . $k->libro->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.jpg') ?>'" alt="Card image cap">

                                    <div class="card-img-overlay card-body d-flex flex-column">
                                        <?= Html::a(
                                            $k->libro->nombre,
                                            ['libros/view', 'id' => $k->libro->id],
                                            [
                                                'class' => 'btn btn-dark btn-block mt-auto card-text text-light linkdata',
                                                'id' => 'libro_' . $k->libro_id
                                            ]
                                        ) ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endif ?>

                <?php if (isset($show)) : ?>
                    <br><br>
                    <div>
                        <h4>Tu cine y series: </h4>
                        <hr>
                        <div class="row">
                            <?php foreach ($show as $k) : ?>
                                <div class="card col-md-2 border-0">
                                    <img class="card-img-top mw-100 mh-100" style="max-height: 100%" src="<?= Yii::getAlias('@imgCineUrl/' . $k->objetos->id . '.jpg') ?>" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.jpg') ?>'" alt="Card image cap">

                                    <div class="card-img-overlay card-body d-flex flex-column">
                                        <?= Html::a(
                                            $k->objetos->nombre,
                                            ['shows/view', 'id' => $k->objetos->id],
                                            [
                                                'class' => 'btn btn-dark btn-block mt-auto card-text  text-light linkdata',
                                                'id'  => 'cine_' . $k->objetos_id
                                            ]
                                        ) ?>
                                    </div>
                                </div>
                            <?php endforeach ?>
                        </div>
                    </div>
                <?php endif ?>
            </div>
        </div>
    </div>