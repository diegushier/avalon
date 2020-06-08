<?php

namespace app\controllers;

use app\models\Libros;
use app\models\Shows;
use Yii;
use yii\web\Controller;
use yii\web\Response;

class UnseenController extends Controller
{
    public function actionIndex($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (isset($id)) {
            $libros = Libros::find()
                ->select('libros.id, libros.nombre')
                ->joinWith('usuariolibros u')
                ->where(['!=', 'u.usuario_id', $id])
                ->orWhere('u.usuario_id is null')
                ->limit(4)
                ->all();
                
                $shows = Shows::find()
                ->select('shows.id, shows.nombre, shows.tipo')
                ->joinWith('usuarioshows u')
                ->where(['!=', 'u.usuario_id', $id])
                ->orWhere('u.usuario_id is null')
                ->limit(4)
                ->all();
        }

        return $data = [$libros, $shows];
    }
}
