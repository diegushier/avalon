<?php

namespace app\controllers;

use app\models\Calendar;
use app\models\ImageForm;
use app\models\Paises;
use Yii;
use app\models\Shows;
use app\models\ShowsSearch;
use app\models\Usuarios;
use app\models\Usuarioseguimiento;
use app\models\Usuarioshows;
use app\models\Valoraciones;
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
        $params = Yii::$app->request->get();
        $dataName = Yii::$app->request->get('dataName', '');
        $model = Shows::find();
        $render = [];

        if ($params) {
            $dataName = $dataName;
            $render = ['dataName' => $dataName];

            $model->where(['ilike', 'nombre', $dataName]);
            $model->andFilterWhere(['ilike', 'tipo', 'cine']);
        }

        $sort = new Sort([
            'attributes' => [
                'nombre',
                'productora' => [
                    'asc' => ['productora_id'  => SORT_ASC],
                    'desc' => ['productora_id'  => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Productora'
                ]
            ]
        ]);

        $model->orderBy($sort->orders);

        $render += [
            'shows' =>  $model->all(),
            'sort' => $sort,
        ];

        return $this->render('peliculas', $render);
    }

    /**
     * Listado de todas las series.
     * @return mixed
     */
    public function actionSeries()
    {
        $params = Yii::$app->request->get();
        $dataName = Yii::$app->request->get('dataName', '');
        $model = Shows::find();
        $render = [];

        if ($params) {
            $dataName = $dataName;
            $render = ['dataName' => $dataName];

            $model->where(['ilike', 'nombre', $dataName]);
            $model->andFilterWhere(['ilike', 'tipo', 'serie']);
        }

        $sort = new Sort([
            'attributes' => [
                'nombre',
                'fecha' => [
                    'asc' => ['genero_id'  => SORT_ASC],
                    'desc' => ['genero_id'  => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Genero'
                ]
            ]
        ]);

        $model->orderBy($sort->orders);

        $render += [
            'shows' =>  $model->all(),
            'sort' => $sort,
        ];

        return $this->render('series', $render);
    }

    protected function search($tipo, $params, $dataName)
    {
        $model = Shows::find()
            ->joinWith(['productora p']);
        $render = [];

        if ($params) {
            $dataName = $dataName;
            $render = ['dataName' => $dataName];

            $model->where(['ilike', 'nombre', $dataName]);
            $model->where(['ilike', 'p.nombre', $dataName]);
        }

        $sort = new Sort([
            'attributes' => [
                'nombre',
                'fecha' => [
                    'asc' => ['genero_id'  => SORT_ASC],
                    'desc' => ['genero_id'  => SORT_DESC],
                    'default' => SORT_ASC,
                    'label' => 'Genero'
                ]
            ]
        ]);

        $model->orderBy($sort->orders);

        $render += [
            'shows' =>  $model->all(),
            'sort' => $sort,
        ];

        return $render;
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
        $media = $model->getMedia($model->id);
        $params = Yii::$app->request->post();
        $val = new Valoraciones();

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
        $comented = Valoraciones::find()
            ->where(['usuario_id' => Yii::$app->user->id])
            ->andWhere(['objetos_id' => $model->id])
            ->one();

        $render = [
            'model' => $model,
            'productora' => $productora->nombre,
            'pais' => $pais,
            'generos' => $generos,
            'duenio' => $duenio,
            'criticas' => $criticas,
            'val' => $val,
            'sort' => $sort,
            'comented' => $comented
        ];

        if ($media) {
            $render += [
                'media' => $media,
            ];
        }

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
    public function actionCreate($tipo)
    {
        $model = new Shows();
        $imagen = new ImageForm();
        $empresa = Usuarios::findOne(Yii::$app->user->id)->getEmpresas()->one()->id;
        $paises = Paises::lista();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (Yii::$app->request->post()) {
                if ($model->fecha !== '') {
                    $calendar = new Calendar();
                    $calendar->name = $model->nombre;
                    $calendar->date = $model->fecha;
                    $calendar->create($model);
                }
                $imagen->imagen = UploadedFile::getInstance($imagen, 'imagen');
                $imagen->upload($model->id, 'cine');
            }

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'imagen' => $imagen,
            'empresa' => $empresa,
            'paises' => $paises,
            'tipo' => $tipo
        ]);
    }

    public function actionSeg($id, $tipo, $seguimiento_id)
    {
        $shows = Usuarioshows::find()->where([
            'objetos_id' => $id, 'usuario_id' => Yii::$app->user->id
        ])->one();

        if ($shows) {
            if ($seguimiento_id !== 'null') {
                $shows->seguimiento_id = $seguimiento_id;
                $shows->update();
            } else {
                $shows->delete();
            }
        } else {
            $shows = new Usuarioshows();
            $shows->objetos_id = $id;
            $shows->usuario_id = Yii::$app->user->id;
            $shows->seguimiento_id = $seguimiento_id;
            $shows->tipo = $tipo;
            $shows->save();
        }
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
        $paises = Paises::lista();

        if (Yii::$app->request->post()) {
            $imagen->imagen = UploadedFile::getInstance($imagen, 'imagen');
            if ($imagen->imagen !== null) {
                $imagen->upload($id, $tipo);
            }
        }

        Yii::debug(Yii::$app->request->post());
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if (($model->fecha !== '' && $model->fecha !== $fecha) || $model->nombre !== $nombre) {
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
            'paises' => $paises
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
        $imagen = new ImageForm();
        $tipo = $model->tipo === 'serie';
        if ($model->getListacapitulos()->exists()) {
            Yii::$app->session->setFlash('error', 'No es posible borrar esa serie porque contiene capítulos.');
            return $this->redirect(['view', 'id' => $model->id]);
        }

        $imagen->delete($model->id, 'cine');

        $generos = $model->getListageneros()->all();
        foreach ($generos as $generos) {
            $generos->delete();
        }

        $reparto = $model->getRepartos()->all();
        foreach ($reparto as $reparto) {
            $reparto->delete();
        }

        $valoraciones = $model->getValoraciones()->all();
        foreach ($valoraciones as $valoraciones) {
            $valoraciones->delete();
        }

        if (isset($model->evento_id)) {
            $calendar = new Calendar();
            $calendar->delete($model->evento_id);
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
