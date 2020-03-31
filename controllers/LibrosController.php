<?php

namespace app\controllers;

use app\models\Generos;
use app\models\Integrantes;
use Yii;
use app\models\Libros;
use app\models\LibrosSearch;
use app\models\Paises;
use app\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;

/**
 * LibrosController implements the CRUD actions for Libros model.
 */
class LibrosController extends Controller
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
     * Lists all Libros models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new LibrosSearch();
        $libros = $searchModel->getObjetos(Yii::$app->request->queryParams);

        return $this->render('index', [
            'libros' =>  $libros,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Displays a single Libros model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = Libros::findOne($id);
        $productora = $model->getEditorial()->one();
        $autor = $model->getAutor()->one();
        $pais = Paises::findOne($model->pais_id);
        $genero = $model->getGenero()->one();
        $duenio = $productora->entidad_id;

        $render = [
            'model' => $model,
            'autor' => $autor->nombre,
            'productora' => $productora->nombre,
            'pais' => $pais->nombre,
            'genero' => $genero->nombre,
            'duenio' => $duenio
        ];

        return $this->render('view', $render);
    }

    public function actionResumen($id)
    {
        $model = Libros::findOne($id);
        $content = file_get_contents(Yii::getAlias('@resumen/' . $id . '.txt'));
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE, // leaner size using standard fonts
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'options' => [
                // any mpdf options you wish to set
            ],
            'methods' => [
                'SetTitle' => $model->nombre,
                'SetAuthor' => $model->getAutor()->one()->nombre,
            ]
        ]);
        return $pdf->render();
    }

    /**
     * Creates a new Libros model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Libros();

        $editorial = Usuarios::obtainEmpresa()->one()->id;
        $paises = Paises::lista();
        $autor = Integrantes::lista();
        $genero = Generos::lista();
        

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'editorial' => $editorial,
            'paises' => $paises,
            'genero' => $genero,
            'autor' => $autor,
        ]);
    }

    /**
     * Updates an existing Libros model.
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
     * Deletes an existing Libros model.
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
     * Finds the Libros model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Libros the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Libros::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
