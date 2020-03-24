<?php

use kartik\sidenav\SideNav;
use yii\bootstrap4\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Objetos */

$this->title = $model->nombre;
$this->params['breadcrumbs'][] = ['label' => ucwords($tipo), 'url' => ['objetos/' . lcfirst($tipo)]];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);


$back = "$('body').css('background-image', 'url( " . Yii::getAlias('@imgBackUrl/' . $model->id . '.jpg') . ")')
         $('#back').css('background-color', '#fff')
";

$this->registerJs($back);
?>
<div class="objetos-view">
    <?php echo $this->render('_searchview', ['model' => $model, 'tipo' => $tipo]); ?>

    <div class="row">
        <img src="<?= Yii::getAlias('@imgUrl/' . $model->id . '.jpg') ?>" class="col-lg-3  m-3 p-0 shadow" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'">
        <div class="views-container mt-3 mb-3 col-lg-3">
            <table class="table ">
                <tbody>
                    <tr>
                        <td>Nombre</td>
                        <td><?= $model->nombre ?></td>
                    </tr>

                    <tr>
                        <td>Autor</td>
                        <td><?= 'alguien' ?></td>
                    </tr>

                    <tr>
                        <td>ISBN</td>
                        <td><?= $model->isbn ?></td>
                    </tr>

                    <tr>
                        <td>Pais</td>
                        <td><?= $pais ?></td>
                    </tr>
<!-- 
                    <tr>
                        <td>Sinopsis:</td>
                    </tr>
                    <tr>
                        <td><?= $model->sinopsis ?></td>
                    </tr> -->

                </tbody>
            </table>

            <h5>Sinopsis</h5>
            <p><?= $model->sinopsis ?></p>
        </div>
    </div>


</div>