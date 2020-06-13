<?php

namespace app\controllers;

use Yii;
use app\models\Empresas;
use app\models\Paises;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * EmpresasController implements the CRUD actions for Empresas model.
 */
class EmpresasController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
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
     * Displays a single Empresas model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $pais = Paises::findOne($model->id);
        return $this->render('view', [
            'model' => $model,
            'pais' => $pais,
        ]);
    }

    public function actionLista()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        return Paises::find()->select('nombre')->all();
    }

    public function actionSearch($entidad)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Empresas::find()->where(['entidad_id' => $entidad])->one();
        if (isset($model)) {
            return $model;
        } else {
            return false;
        }
    }

    /**
     * Creates a new Empresas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id, $name, $pais_id)
    {
        $model = new Empresas();
        $model->nombre = $name;
        $model->entidad_id = $id;
        $model->pais_id = $pais_id;

        Yii::$app->response->format = Response::FORMAT_JSON;
        return $pais_id;
    }

    /**
     * Updates an existing Empresas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $action)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'action' => $action
        ]);
    }

    /**
     * Deletes an existing Empresas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete()
    {

        $params = Yii::$app->request->post();
        Yii::debug($params);
        if ($params) {
            $model = Empresas::findOne(['entidad_id' => $params['id']]);
            $model->entidad_id = null;

            if ($model->getShows()->exists() || $model->getLibros()->exists()) {
                if ($model->update()) {
                    Yii::$app->session->setFlash('success', 'La empresa ha posteado, por lo que su usuario solo ha sido desvinculado.');
                    return $this->redirect(['/site/index']);
                } else {
                    Yii::$app->session->setFlash('error', 'No ha sido posible desvincular su usuario.');
                }
            } elseif ($model->delete()) {
                Yii::$app->session->setFlash('success', 'La empresa se ha borrado.');
                return $this->redirect(['/site/index']);
            } else {
                Yii::$app->session->setFlash('error', 'Ha ocurrido un error interno.');
            }
        }


        return $this->redirect(['index']);
    }

    /**
     * Finds the Empresas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Empresas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Empresas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
