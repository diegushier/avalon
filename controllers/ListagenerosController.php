<?php

namespace app\controllers;

use app\models\Generos;
use app\models\GenerosForm;
use app\models\Listageneros;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ListacapitulosController implements the CRUD actions for Listacapitulos model.
 */
class ListagenerosController extends Controller
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
        $model = new Listageneros();
        $generos = Generos::lista();
        
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
                return $this->redirect(['/shows/view', 'id' => $id]);
        }

        return $this->render('create', [
            'model' => $model,
            'generos' => $generos,
            'id' => $id
        ]);
    }

    /**
     * Borra un capítulo de una serie.
     *
     * @param [int] $id
     * @param [string] $serie
     */
    public function actionDelete($id, $serie)
    {
        $model = Listageneros::find()->where('objetos_id = ' . $serie)->andWhere('genero_id = ' . $id)->one();
        $model->delete();
        return $this->redirect(['/shows/view', 'id' => $serie]);
    }

    /**
     * Updates an existing Listacapitulos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $serie)
    {
        $model = $this->findModel($id);
        $generos = Generos::lista();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'generos' => $generos,
            'id' => $serie,
        ]);
    }

    /**
     * Finds the Capitulos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Capitulos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Listageneros::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
