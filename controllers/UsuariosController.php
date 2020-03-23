<?php

namespace app\controllers;

use app\models\Empresas;
use app\models\Paises;
use app\models\Usuarios;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap4\Alert;
use yii\db\Query;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;
use yii\web\Response;

class UsuariosController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['registrar'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['?'],
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


    public function actionRegistrar()
    {
        $model = new Usuarios(['scenario' => Usuarios::SCENARIO_CREAR]);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha creado el usuario correctamente.');
            return $this->redirect(['site/login']);
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $paises = Paises::lista();


        return $this->render('registrar', [
            'model' => $model,
            'paises' => ['' => ''] + $paises,
        ]);
    }

    public function actionDelete($id)
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            throw new HttpException(400, 'Usuario no encontrado.');
        }

        if (Yii::$app->request->isPost) {
            $model = Usuarios::findOne($id);
            $model->delete();
        }
        // $this->findModel($id)->delete();

        return $this->redirect(['/site/login']);
    }

    public function actionModificar($id = null)
    {
        if ($id === null) {
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->setFlash('error', 'Debe estar logueado.');
                return $this->goHome();
            } else {
                $model = Yii::$app->user->identity;
            }
        } else {
            $model = Usuarios::findOne($id);
        }

        $model->scenario = Usuarios::SCENARIO_UPDATE;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha modificado correctamente.');
            return $this->goHome();
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }


        $exists = Usuarios::findOne(Yii::$app->user->id)->getEmpresas()->exists();
        $empresa = (new Query())->from('empresas')->where('entidad_id = ' . Yii::$app->user->id)->all();

        $paises = Paises::lista();

        $model->passwd = '';
        $model->passwd_repeat = '';

        return $this->render('modificar', [
            'model' => $model,
            'paises' => ['' => ''] + $paises,
            'empresa' => $empresa,
            'exists' => $exists,
        ]);
    }
}
