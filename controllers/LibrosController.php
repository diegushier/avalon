<?php

namespace app\controllers;

use app\models\Calendar;
use app\models\Criticas;
use app\models\Generos;
use app\models\ImageForm;
use app\models\Integrantes;
use Yii;
use app\models\Libros;
use app\models\LibrosSearch;
use app\models\Paises;
use app\models\Usuariolibros;
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
        $model = Libros::find()
            ->joinWith(['editorial e', 'autor a']);
        $render = [];

        if (Yii::$app->request->get()) {
            $dataName = Yii::$app->request->get('dataName', '');
            $render = ['dataName' => $dataName];

            $model->where(['ilike', 'nombre', $dataName]);
            $model->where(['ilike', 'e.nombre', $dataName]);
            $model->where(['ilike', 'a.nombre', $dataName]);
        }

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

        $model->orderBy($sort->orders);

        $render += [
            'libros' =>  $model->all(),
            'sort' => $sort,
        ];

        return $this->render('index', $render);
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
        $media = $model->getMedia($model->id);

        $params = Yii::$app->request->post();
        $val = new Criticas();
        if ($val->load($params)) {
            $val->save();
        }

        $sort = new Sort([
            'attributes' => [
                'fecha' => [
                    'asc' => ['fecha'  => SORT_ASC],
                    'desc' => ['fecha'  => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Fecha'
                ],
            ]
        ]);


        $criticas = $model->getCriticasWithUsers($sort);
        $comented = Criticas::find()
            ->where(['usuario_id' => Yii::$app->user->id])
            ->andWhere(['libro_id' => $model->id])
            ->one();

        $this->newComment(Yii::$app->request->post());

        $render = [
            'model' => $model,
            'autor' => $autor->nombre,
            'productora' => $productora->nombre,
            'pais' => $pais->nombre,
            'genero' => $genero,
            'duenio' => $duenio,
            'criticas' => $criticas,
            'val' => $val,
            'sort' => $sort,
            'comented' => $comented,
        ];

        if ($media) {
            $render += [
                'media' => $media,
            ];
        }

        return $this->render('view', $render);
    }

    protected function newComment($params)
    {
        $model = new Criticas();
        $model->load($params);
        $model->save();
    }

    public function actionSeg($id, $tipo, $seguimiento_id)
    {
        $libros = Usuariolibros::find()
            ->where(['libro_id' => $id])
            ->andWhere(['usuario_id' => Yii::$app->user->id])
            ->andWhere(['tipo' => $tipo])->one();

        if ($libros) {
            if ($seguimiento_id !== 'null') {
                $libros->seguimiento_id = $seguimiento_id;
                $libros->update();
            } else {
                $libros->delete();
            }
        } else {
            $libros = new Usuariolibros();
            $libros->libro_id = $id;
            $libros->usuario_id = Yii::$app->user->id;
            $libros->seguimiento_id = $seguimiento_id;
            $libros->tipo = $tipo;
            $libros->save();
        }
    }

    /**
     * Abre una nueva pestaña en el navegador para la vista del resumen de un Objeto libro.
     *
     * @param [int] $id
     */
    public function actionResumen($id)
    {
        $model = Libros::findOne($id);
        $content = file_get_contents(Yii::getAlias('@resumen/' . $id . '.txt'));
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'destination' => Pdf::DEST_BROWSER,
            'content' => $content,
            'options' => [],
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
    public function actionCreate($scenario)
    {
        $model = new Libros();
        $imagen = new ImageForm();
        $calendar = new Calendar();
        $editorial = Usuarios::obtainEmpresa()->one()->id;
        $paises = Paises::lista();
        $autor = Integrantes::lista();
        $genero = Generos::lista();
        $tipo = 'libro';
        $params = Yii::$app->request->post();

        if ($model->load($params) && $model->save()) {
            if ($params) {
                if ($model->fecha !== '') {
                    $calendar->name = $model->nombre;
                    $calendar->date = $model->fecha;
                    $calendar->create($model);
                }

                if ($params['ImageForm']['imagen'] !== '') {
                    $imagen->imagen = UploadedFile::getInstance($imagen, 'imagen');
                    $imagen->upload($model->id, $tipo);
                }
            }
            return $this->redirect(['view', 'id' => $model->id]);
        }
        $render = [
            'model' => $model,
            'imagen' => $imagen,
            'editorial' => $editorial,
            'paises' => $paises,
            'genero' => $genero,
            'autor' => $autor,
            'scenario' => $scenario
        ];

        return $this->render('create', $render);
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

        $this->deleteAlters($model->getCriticas()->all());
        $this->deleteAlters($model->getNotificacioneslibros()->all());
        $this->deleteAlters($model->getUsuariolibros()->all());

        if (isset($model->evento_id)) {
            $calendar = new Calendar();
            $calendar->delete($model->evento_id);
        }

        $imagen = new ImageForm();
        $imagen->delete($model->id, 'libro');
        $model->delete();

        return $this->redirect(['index']);
    }

    protected function deleteAlters($data)
    {
        if ($data) {
            foreach ($data as $k) {
                $k->delete();
            }
        }
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
