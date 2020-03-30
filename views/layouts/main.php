<?php

/* @var $this \yii\web\View */
/* @var $content string */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\bootstrap4\Nav;
use yii\bootstrap4\NavBar;
use yii\bootstrap4\Breadcrumbs;
use app\assets\AppAsset;
use app\controllers\SiteController;
use app\models\Empresas;
use app\models\Usuarios;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">

<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>

<body>
    <?php $this->beginBody() ?>

    <div class="wrap">
        <?php
        NavBar::begin([
            'brandLabel' => 'Avalon',
            'brandUrl' => Yii::$app->homeUrl,
            'options' => [
                'class' => 'navbar-dark bg-dark navbar-expand-md fixed-top',
            ],
            'collapseOptions' => [
                'class' => 'justify-content-end',
            ],
        ]);

        $menu = [
            ['label' => 'Libros', 'url' => ['/libros/index']],
            ['label' => 'Peliculas', 'url' => ['/objetos/peliculas']],
            ['label' => 'Series', 'url' => ['/objetos/series']]
        ];

        if (Yii::$app->user->isGuest) {
            $menu[] = ['label' => 'Usuarios', 'items' => [
                ['label' => 'Login', 'url' => ['/site/login']],
                ['label' => 'Regitrarse', 'url' => ['/usuarios/registrar']],
            ]];
        } elseif (Yii::$app->user->identity->clave === null) {
            $menu[] = ['label' => Yii::$app->user->identity->nickname, 'items' => [
                ['label' => 'Modificar', 'url' => ['/usuarios/modificar']],
                Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Salir-Desconectar',
                        ['class' => 'dropdown-item'],
                    )
                    . Html::endForm(),
                ['label' => 'Crear', 'url' => ['/objetos/create']],

            ]];
        } else {
            $menu[] = ['label' => Yii::$app->user->identity->nickname, 'items' => [
                ['label' => 'Modificar', 'url' => ['/usuarios/modificar']],
                Html::beginForm(['/site/logout'], 'post')
                    . Html::submitButton(
                        'Salir-Desconectar',
                        ['class' => 'dropdown-item'],
                    )
                    . Html::endForm(),
            ]];
        }

        echo Nav::widget([
            'options' => ['class' => 'navbar-nav'],
            'items' => $menu,
        ]);
        NavBar::end();
        ?>

        <div id="back" class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
            <?= Alert::widget() ?>
            <?= $content ?>
        </div>
    </div>

    <footer class="footer bg-dark">
        <div class="container">
            <p class="float-left">&copy; Proyecto Final Integrado Avalon <?= date('Y') ?></p>
        </div>
    </footer>

    <?php $this->endBody() ?>
</body>

</html>
<?php $this->endPage() ?>