<?php

namespace app\controllers;

use app\models\Calendar;
use app\models\Generos;
use app\models\ImageForm;
use app\models\Integrantes;
use Yii;
use app\models\Libros;
use app\models\LibrosSearch;
use app\models\Paises;
use app\models\Shows;
use app\models\Usuarios;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\mpdf\Pdf;
use yii\data\Sort;
use yii\web\UploadedFile;

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
        $params = Yii::$app->request->queryParams;
        $sort = new Sort([
            'attributes' => [
                'nombre',
                'genero_id' => [
                    'asc' => ['genero_id'  => SORT_ASC],
                    'desc' => ['genero_id'  => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Genero'
                ]
            ]
        ]);
        $libros = $searchModel->getObjetos($params, $sort);
        return $this->render('index', [
            'libros' =>  $libros,
            'sort' => $sort,
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
            'genero' => $genero,
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
        $imagen = new ImageForm();
        $calendar = new Calendar();
        $editorial = Usuarios::obtainEmpresa()->one()->id;
        $paises = Paises::lista();
        $autor = Integrantes::lista();
        $genero = Generos::lista();
        $tipo = 'libro';

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post()) {
                if ($model->fecha !== '') {
                    $calendar->name = $model->nombre;
                    $calendar->date = $model->fecha;
                    $calendar->create($model);
                }

                $imagen->imagen = UploadedFile::getInstance($imagen, 'imagen');
                $imagen->upload($model->id, $tipo);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'imagen' => $imagen,
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
        $imagen = new ImageForm();
        $tipo = 'libro';
        $calendar = new Calendar();
        $editorial = Usuarios::obtainEmpresa()->one()->id;
        $paises = Paises::lista();
        $autor = Integrantes::lista();
        $genero = Generos::lista();
        $fecha = $model->fecha;
        $nombre = $model->nombre;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $imagen->imagen = UploadedFile::getInstance($imagen, 'imagen');
            $imagen->upload($id, $tipo);
            if (($model->fecha !== $fecha && $model->fecha !== '') || $model->nombre !== $nombre) {
                $calendar->name = $model->nombre;
                $calendar->date = $model->fecha;
                $calendar->update($model, $model->evento_id);
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'imagen' => $imagen,
            'editorial' => $editorial,
            'paises' => $paises,
            'genero' => $genero,
            'autor' => $autor,
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
        $model = $this->findModel($id);
        if (isset($model->evento_id)) {
            $calendar = new Calendar();
            $calendar->delete($model->evento_id);
        }
        $model->delete();

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
