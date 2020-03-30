<?php

namespace app\controllers;

use app\models\Paises;
use Yii;
use app\models\Shows;
use app\models\ShowsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ShowsController implements the CRUD actions for Shows model.
 */
class ShowsController extends Controller
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
     * Lists all Shows models.
     * @return mixed
     */
    public function actionPeliculas()
    {
        $searchModel = new ShowsSearch();
        $peliculas = $searchModel->getObjetos(Shows::PELICULAS, Yii::$app->request->queryParams);

        return $this->render('peliculas', [
            'peliculas' =>  $peliculas,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionSeries()
    {
        $searchModel = new ShowsSearch();
        $series = $searchModel->getObjetos(Shows::SERIES, Yii::$app->request->queryParams);

        return $this->render('series', [
            'series' =>  $series,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * Displays a single Shows model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Shows::findOne($id);
        $productora = $model->getProductora()->one();
        $pais = Paises::findOne($model->pais_id);
        $duenio = $productora->entidad_id;

        return $this->render('view', [
            'model' => $model,
            'productora' => $productora->nombre,
            'pais' => $pais->nombre,
            'duenio' => $duenio
        ]);
    }

    /**
     * Creates a new Shows model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Shows();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Shows model.
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
     * Deletes an existing Shows model.
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
     * Finds the Shows model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Shows the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Shows::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}