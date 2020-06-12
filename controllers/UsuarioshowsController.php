<?php

namespace app\controllers;

use Yii;
use app\models\Usuarioshows;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * Implementa las acciones requeridas para el correcto funcionamiento del modelo UsuarioSeguimiento.
 */
class UsuarioshowsController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['create', 'update', 'delete'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['create', 'update', 'delete'],
                        'roles' => ['@'],
                    ],
                    // everything else is denied by default
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Genera una nueva fila en la base de datos.
     */
    public function actionCreate()
    {
        $model = new Usuarioshows();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Modifica la línea requeria en la correspondiente tabla de la base de datos..
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Elimina la fila correspondiente fila en la base de datos, en caso de que la columna de seguimientoeste vacía.
     */
    public function actionDelete($id, $objeto)
    {
        $model = $this->findModel($id);
        
        if ($model->seguimiento_id === null) {
            $model->delete();
            return $this->redirect(['/libros/view', 'id' => $objeto]);
        } else {
            Yii::$app->session->setFlash('error', 'Ha ocurrido un error en el servidor. Intentelo más tarde.');
            return $this->redirect(['/libros/view', 'id' => $objeto]);
        }
    }

    protected function findModel($id)
    {
        if (($model = Usuarioshows::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
