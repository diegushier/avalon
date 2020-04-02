<?php

namespace app\controllers;

use app\models\Capitulos;
use app\models\CapitulosForm;
use Yii;
use app\models\Listacapitulos;
use app\models\ListacapitulosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ListacapitulosController implements the CRUD actions for Listacapitulos model.
 */
class ListacapitulosController extends Controller
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
     * Creates a new Listacapitulos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($id)
    {
        $model = new CapitulosForm();
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->create($model)) {
                return $this->redirect(['/shows/view', 'id' => $model->objetos_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
            'id' => $id
        ]);
    }

    /**
     * Updates an existing Listacapitulos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
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
     * Deletes an existing Listacapitulos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $serie)
    {
        $model = $this->findModel($id);
        $capitulo = Capitulos::findOne($model->capitulo_id);
        if ($model->delete()) {
            $capitulo->delete();
            Yii::$app->session->setFlash('success', 'El capitulo ha sido eliminado');
        } else {
            Yii::$app->session->setFlash('Error', 'No ha sido posible borrar el capítulo.');
        }
        
        return $this->redirect(['/shows/view', 'id' => $serie]);
    }

    /**
     * Finds the Listacapitulos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Listacapitulos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Listacapitulos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
