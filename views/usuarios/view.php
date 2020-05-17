<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\RegistrarForm */

use yii\bootstrap4\Html as Bootstrap4Html;
use yii\helpers\Html;
use yii\helpers\Url;

$clave = $model->clave === null;
$this->title = $model->nickname;

$urlGet = Url::to(['seguidores/checker']);
$urlSet = Url::to(['seguidores/follow']);
$urlBlock = Url::to(['seguidores/block']);
$id = $model->id;
$user_id = Yii::$app->user->identity->id;

$js = <<<EOT
function check(){
    $.ajax({
        method: 'GET',
        url: "$urlGet",
        data: {
            id: "$id",
            seguidor: "$user_id",
        },
        success:function(data) {
            if (data) {
                $('#follow').html('No seguir');
            } else if (data === null) {
                $('#follow').remove();
            } else {
                $('#follow').html('Seguir');
            }
    
            if (data === null) {
                $('#block').html('Desbloquear')
            } else {
                $('#block').html('Bloquear')
            }
        },  
    })
}

// Evento, si el usuario target bloquea al usuario actual, redirigir al index.

$('#block').click(() => {
    $.ajax({
        method: 'GET',
        url: "$urlBlock",
        data: {
            id: "$id",
            user_id : "$user_id"
        },
        success:function() {
            check()
        },  
    })
})

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

$this->registerJs($js);
?>
<div class="site-view row m-auto">
    <div class="col-sm-12 col-lg-2 border-right">
        <?= Html::a(
            'Prox. lanzamientos',
            ['/site/index'],
            [
                'class' => 'btn btn-orange w-100',
            ]
        ) ?>
        <?= Html::a(
            'Tu lista',
            ['/usuarios/lista'],
            [
                'class' => 'btn btn-orange w-100 mt-1',
            ]
        ) ?>
    </div>
    <div class="row col-lg-8 col-sm-12">

        <div class="col-lg-4 col-sm-12">
            <img src="<?= Yii::getAlias('@imgUserUrl/' . $model->id . '.jpg') ?>" class="mt-3 ml-1 mb-3 mr-3 p-1 w-100 shadow" onerror="this.src = '<?= Yii::getAlias('@imgUrl/notfound.png') ?>'">
            <div class="row">
                <?php if (Yii::$app->user->identity->id !== $model->id) : ?>
                    <button class="btn btn-dark col-lg-5 ml-lg-3 mb-lg-1" id="follow"></button>

                    <?= Html::a(
                        'Seguidores',
                        ['/seguidores/view'],
                        [
                            'class' => 'btn btn-dark col-lg-5 mb-lg-1 ml-lg-1',
                        ]
                    ) ?>
                    <?= Html::a(
                        'Siguiendo',
                        ['/seguidores/siguiendo'],
                        [
                            'class' => 'btn btn-dark col-lg-10 ml-lg-3',
                        ]
                    ) ?>
                <?php else : ?>
                    <?= Html::a(
                        'Seguidores',
                        ['/seguidores/view'],
                        [
                            'class' => 'btn btn-dark col-lg-5 ml-lg-3',
                        ]
                    ) ?>
                    <?= Html::a(
                        'Siguiendo',
                        ['/seguidores/siguiendo'],
                        [
                            'class' => 'btn btn-dark col-lg-5 ml-lg-2',
                        ]
                    ) ?>
                <?php endif ?>

            </div>
            <div class="modal fade" id="borrarUsuario" tabindex="-1" role="dialog" aria-labelledby="borrarUsuarioCenterTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="borrarUsuarioLongTitle">Borrar cuenta</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            ¿Esta usted seguro de eliminar este usuario?
                            La acción será irreversible.

                            <div>
                                <input type="text" id='deleteUser' placeholder="Escriba su nombre para comfirmar" class="col-12 form-control mt-2">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <?= Html::a('Eliminar', ['/usuarios/delete'], [
                                'id' => 'comfirmDeleteUser',
                                'class' => 'btn btn-danger',
                                'data' => [
                                    'method' => 'post',
                                    'params' => ['id' => Yii::$app->user->id],
                                ],
                            ]) ?>

                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-7 col-sm-12 mt-3">
            <table class="table">
                <div class="row">
                    <h4 class="col-lg-4">Usuario:</h4>

                    <?php if (Yii::$app->user->identity->id === $model->id) : ?>
                        <?= Html::a(
                            '<i class="fas fa-cog"></i>',
                            ['/usuarios/modify'],
                            [
                                'class' => 'btn btn-orange col-lg-1 ml-1 mb-1',
                            ]
                        ) ?>
                        <button type="button" class="btn btn-danger col-lg-1 ml-1 mb-1" data-toggle="modal" data-target="#borrarUsuario">
                            <i class="fa fa-times"></i>
                        </button>
                    <?php else : ?>
                        <button type="button" class="btn btn-danger ml-1 mb-1" id="block">
                        </button>
                    <?php endif ?>
                </div>

                <tbody>
                    <tr>
                        <td>Nickname</td>
                        <td><?= $model->nickname ?></td>
                    </tr>
                    <tr>
                        <td>Correo:</td>
                        <td><?= $model->correo ?></td>
                    </tr>
                    <tr>
                        <td>Pais:</td>
                        <td><?= $pais ?></td>
                    </tr>
                    <tr>
                        <td>Auntenticado</td>
                        <th><?= $clave ? Html::label('<i class="fas fa-check"></i>', null, ['style' => 'color: green']) : Html::label('<i class="fas fa-times"></i>', null, ['style' => 'color:red']) ?></th>
                    </tr>
                </tbody>

                <?php if ($clave) : ?>
                    <table class="table">
                        <h4>Empresa:</h4>
                        <tbody>
                            <tr>
                                <td>Nombre</td>
                                <td><?= $empresa->nombre ?></td>
                            </tr>
                            <tr>
                                <td>Pais</td>
                                <td><?= $emp_pais ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php endif ?>
            </table>
        </div>
    </div>
</div>