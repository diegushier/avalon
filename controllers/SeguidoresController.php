<?php

namespace app\controllers;

use Yii;
use app\models\Seguidores;
use app\models\SeguidoresSearch;
use app\models\Seguimiento;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * SeguidoresController implements the CRUD actions for Seguidores model.
 */
class SeguidoresController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }


    /**
     * Muestra los usuarios que siguen al usuario target
     *
     * @param int $id
     * @return mixed
     */
    public function actionView($id = null)
    {
        $user_id = $id === null ? Yii::$app->user->identity->id : $id;
        $model = Seguidores::find()->where(['user_id' => $user_id])->joinWith('seguidor s')->all();
        $lista = Seguidores::find()->select('seguidor_id')->where(['user_id' => $id])->all();
        return $this->render('view', [
            'model' => $model,
            'id' => $user_id,
            'lista' => $lista,
        ]);
    }

    /**
     * Muestra los usuarios a los que sigue el usuario target
     *
     * @param int $id
     * @return mixed
     */
    public function actionSiguiendo($id = null)
    {
        $seguidor_id = $id === null ? Yii::$app->user->identity->id : $id;
        $model = Seguidores::find()->where(['seguidor_id' => $seguidor_id])->joinWith('seguidor s')->all();
        $lista = Seguidores::find()->select('user_id')->where(['seguidor_id' => $id])->all();
        return $this->render('siguiendo', [
            'model' => $model,
            'id' => $seguidor_id,
            'lista' => $lista,
        ]);
    }

    /**
     * Comprueba si el usuario actual esta siguiendo o no al usuario target
     *
     * @param int $id usuario target
     * @param int $seguidor usuario actual
     * @return boolean
     */
    public function actionChecker($id, $seguidor)
    {
        $data = false;
        $model = Seguidores::find()
            ->where(['user_id' => $id])
            ->andWhere(['seguidor_id' => $seguidor])
            ->one();

        if ($model && $model->bloqueado === true) {
            $data = null;
        } elseif ($model) {
            $data = true;
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        return $data;
    }

    /**
     * Bloquea o desbloquea al usuario target se seguir al usuario actual.
     * @param int $id usuario target
     * @param int $seguidor usuario actual
     */
    public function actionBlock($id, $user_id)
    {
        $model = Seguidores::find()->where(['user_id' => $id])->andWhere(['seguidor_id' => $user_id])->one();
        if ($model) {
            if ($model->bloqueado === true) {
                $model->bloqueado = null;
            } else {
                $model->bloqueado = true;
            }
            
            $model->save();
        }
    }

    /**
     * Seguie o deja de seguir al usuario target
     *
     * @param int $id usuario target
     * @param int $user_id usuario actual
     * @return render view
     */
    public function actionFollow($id, $user_id)
    {
        $model = Seguidores::find()->where(['user_id' => $id])->andWhere(['seguidor_id' => $user_id])->one();
        if ($model && $model->bloqueado === null) {
            return $this->render('site/index');
        }

        if (!$model) {
            $model->delete();
        } else {
            $model = new Seguidores();
            $model->user_id = $id;
            $model->seguidor_id = $user_id;

            $model->save();
        }
    }

    /**
     * Finds the Seguidores model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Seguidores the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Seguidores::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
