<?php

namespace app\controllers;

use app\models\Libros;
use app\models\Menajes;
use DateTime;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class MensajesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    public function actionCreate($libro)
    {
        $model = new Menajes();
        $user = Yii::$app->user->identity->id;
        $mensaje = 'Queda poco para el estreno de este libro.';

        $model->libro_id = $libro;
        $model->user_id = $user;
        $model->mensaje = $mensaje;

        $model->save();
    }

    public function actionChecker()
    {
        $date = date('Y-m-d', strtotime('+1 week'));
        $data = Menajes::find()->joinWith('libro l')->where('user_id = ' . Yii::$app->user->identity->id)->andWhere(['=', 'fecha', $date])->all();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!empty($data)) {
            return $data;
        }
    }

    public function actionUpdate($id, $modelid)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['shows/view', 'id' => $modelid]);
        }

        return $this->render('update', [
            'model' => $model,
            'modelid' => $modelid
        ]);
    }

    protected function findModel($id)
    {
        if (($model = Menajes::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
