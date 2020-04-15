<?php

namespace app\controllers;

use app\models\Calendar;
use app\models\ImageForm;
use app\models\Paises;
use Yii;
use app\models\Shows;
use app\models\ShowsSearch;
use app\models\Usuarios;
use yii\data\Sort;
use yii\web\UploadedFile;
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
     * Listado de todas las películas.
     * @return mixed
     */
    public function actionPeliculas()
    {
        $searchModel = new ShowsSearch();
        $params = Yii::$app->request->queryParams;
        $sort = new Sort([
            'attributes' => [
                'nombre',
                'fecha' => [
                    'asc' => ['fecha'  => SORT_ASC],
                    'desc' => ['fecha'  => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Fecha'
                ],
            ]
        ]);
        $peliculas = $searchModel->getObjetos(Shows::PELICULAS, $params, $sort);
        return $this->render('peliculas', [
            'peliculas' =>  $peliculas,
            'sort' => $sort,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * Listado de todas las series.
     * @return mixed
     */
    public function actionSeries()
    {
        $searchModel = new ShowsSearch();
        $params = Yii::$app->request->queryParams;
        $sort = new Sort([
            'attributes' => [
                'nombre',
                'fecha' => [
                    'asc' => ['fecha'  => SORT_ASC],
                    'desc' => ['fecha'  => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Fecha'
                ],
            ]
        ]);
        $series = $searchModel->getObjetos(Shows::SERIES, $params, $sort);

        return $this->render('series', [
            'series' =>  $series,
            'sort' => $sort,
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
        $generos = $model->getGeneros()->select('nombre, id')->all();
        $pais = Paises::findOne($model->pais_id)->nombre;
        $duenio = $productora->entidad_id;
        $render = [
            'model' => $model,
            'productora' => $productora->nombre,
            'pais' => $pais,
            'generos' => $generos,
            'duenio' => $duenio,
        ];


        if ($model->tipo === 'serie') {
            $capitulos = $model->getCapitulos()->all();
            $ids = $model->getCapitulos()->select('id')->all();

            $render += [
                'capitulos' => $capitulos,
                'ids' => $ids,
            ];
        }

        return $this->render('view', $render);
    }

    /**
     * Creates a new Shows model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Shows();
        $imagen = new ImageForm();
        $tipo = 'cine';
        $empresa = Usuarios::findOne(Yii::$app->user->id)->getEmpresas()->one()->id;
        $paises = Paises::lista();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post()) {
                if (isset($model->fecha)) {
                    $calendar = new Calendar();
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
            'empresa' => $empresa,
            'paises' => $paises,
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
        $imagen = new ImageForm();
        $tipo = 'cine';
        $empresa = Usuarios::findOne(Yii::$app->user->id)->getEmpresas()->one()->id;
        $fecha = $model->fecha;
        $nombre = $model->nombre;
        $calendar = new Calendar();

        if (Yii::$app->request->post()) {
            $imagen->imagen = UploadedFile::getInstance($imagen, 'imagen');
            $imagen->upload($id, $tipo);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
            'empresa' => $empresa,
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
        $model = $this->findModel($id);
        $tipo = $model->tipo === 'serie';
        if ($model->getListacapitulos()->exists()) {
            Yii::$app->session->setFlash('error', 'No es posible borrar esa serie porque contiene capítulos.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        if (isset($model->evento_id)) {
            $calendar = new Calendar();
            $calendar->delete($model->evento_id);
        }

        $generos = $model->getListageneros()->all();
        foreach ($generos as $generos) {
            $generos->delete();
        }

        $reparto = $model->getRepartos()->all();
        foreach ($reparto as $reparto) {
            $reparto->delete();
        }

        if (!$model->delete()) {
            Yii::$app->session->setFlash('error', 'Ha ocurrido un fallo en el servidor.');
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            if ($tipo) {
                return $this->redirect(['series']);
            } else {
                return $this->redirect(['peliculas']);
            }
        }
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
