<?php

namespace app\controllers;

use app\models\Libros;
use app\models\Notificacionesshows;
use app\models\Shows;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

class NotificacionesshowsController extends Controller
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

    /**
     * Crea una nueva notificación de shows
     *
     * @param integer $show_id
     * @return boolean
     */
    public function actionCreate($show_id)
    {
        $model = new Notificacionesshows();
        $show = Shows::findOne($show_id);
        $user = Yii::$app->user->identity->id;
        $mensaje = 'Estreno: ' . $show->nombre . ' ' . $show->fecha;


        $model->show_id = $show_id;
        $model->user_id = $user;
        $model->mensaje = $mensaje;

        Yii::$app->response->format = Response::FORMAT_JSON;

        if ($model->save()) {
            return true;
        }

        return false;
    }


    /**
     * Comprueba si existen notificaciones referentes a una semana
     *
     * @return model
     */
    public function actionChecker()
    {
        $date = date('Y-m-d', strtotime('+1 week'));
        $data = Notificacionesshows::find()->joinWith('show l')->where('user_id = ' . Yii::$app->user->identity->id)->andWhere(['=', 'fecha', $date])->all();
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (!empty($data)) {
            return $data;
        }
    }

    /**
     * Modifica una notificación de tipo show
     *
     * @param integer $id
     * @param integer $modelid
     * @return mixed
     */
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

    /**
     * Encuentra un modelo según id
     *
     * @param integer id
     */

    protected function findModel($id)
    {
        if (($model = Notificacionesshows::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
