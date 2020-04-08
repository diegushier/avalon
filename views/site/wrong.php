<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

$img = Yii::getAlias('@imgUrl/login.jpg');

$js = "$('body').css({
            backgroundImage : 'url(" . $img . ")'
        ,   backgroundColor : 'black'  
        });
    ";

$this->registerJs($js);
$this->title = 'ERROR';
?>
<div class="site-wrong">
    <div class="container bg-white w-sm w-lg w-xl rounded shadow-sm">
        <div class="mb-3 p-1">
            <h1 class="titles text-center border-bottom-1 pt-3 mb-3"><?= Html::encode($this->title) ?></h1>
        </div>
        <div class="forms m-2 p-3">
            <p>
                Este enlace ya no está disponible, por favor, use el último que se le ha entregado. 
            </p>
            <p>
                En caso de que no lo tenga, vuelva a realizar el mismo procedimiento de recuperación de datos.
            </p>
        </div>
    </div>
</div>