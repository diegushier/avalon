<?php

namespace app\controllers;

use app\models\ImagenForm;
use Yii;
use app\models\Objetos;
use app\models\ObjetosSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ObjetosController implements the CRUD actions for Objetos model.
 */
class ObjetosController extends Controller
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
     * Lists all Books of the models.
     * @return mixed
     */
    public function actionLibros()
    {
        $searchModel = new ObjetosSearch();
        $libros = $searchModel->getObjetos(Objetos::LIBROS, Yii::$app->request->queryParams);
        
        return $this->render('libros', [
            'libros' =>  $libros,
            'tipo' => Objetos::LIBROS,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Lists all Films of the models.
     * @return mixed
     */
    public function actionPeliculas()
    {
        $searchModel = new ObjetosSearch();
        $peliculas = $searchModel->getObjetos(Objetos::PELICULAS, Yii::$app->request->queryParams);

        return $this->render('peliculas', [
            'peliculas' =>  $peliculas,
            'tipo' => Objetos::PELICULAS,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Lists all series as models.
     * @return mixed
     */
    public function actionSeries()
    {
        $searchModel = new ObjetosSearch();

        $series = $searchModel->getObjetos(Objetos::SERIES, Yii::$app->request->queryParams);
        return $this->render('series', [
            'series' =>  $series,
            'tipo' => Objetos::SERIES,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Objetos model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Objetos model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Objetos();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Objetos model.
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
     * Deletes an existing Objetos model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Objetos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Objetos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Objetos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
