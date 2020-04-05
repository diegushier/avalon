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
        $clave = $this->generarClave();
        $model->setAttribute('clave', $clave);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $this->sendMail($model->correo, $clave);
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

    public function actionDelete($id, $entidad = null)
    {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
        } else {
            throw new HttpException(400, 'Usuario no encontrado.');
        }

        if (Yii::$app->request->isPost) {
            $model = Usuarios::findOne($id);
            if ($entidad !== null) {
                $empresa = Empresas::findOne($entidad);
                $empresa->entidad_id = null;
                if ($empresa->update() === false) {
                    Yii::$app->session->setFlash('error', 'No se ha podido eliminar el usuario indicado.');
                }
            }

            if ($model->delete()) {
                Yii::$app->session->setFlash('success', 'El usuario ha sido eliminado.');
            } else {
                Yii::$app->session->setFlash('error', 'No se ha podido eliminar el usuario indicado.');
            }
        }

        return $this->redirect(['/site/login']);
    }

    public function actionModificar($id = null)
    {
        if ($id === null) {
            if (Yii::$app->user->isGuest) {
                Yii::$app->session->setFlash('error', 'Debe estar logueado.');
                return $this->goHome();
            }
        }
        
        $model = Yii::$app->user->identity;
        $model->scenario = Usuarios::SCENARIO_UPDATE;
        $exists = $model->getEmpresas();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha modificado correctamente.');
            return $this->goHome();
        }

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $this->updatearClave($model, Yii::$app->request->post());

        $empresa = $this->updatearEmpresa($exists, $exists->one());
        $paises = Paises::lista();

        $model->passwd = '';
        $model->passwd_repeat = '';
        $render = [
            'model' => $model,
            'paises' => ['' => ''] + $paises,
            'empresa' => $empresa,
        ];

        return $this->render('modificar', $render);
    }

    protected function updatearClave($model, $params)
    {
        if (isset($params['Usuarios']['clave'])) {
            if ($params['Usuarios']['clave'] === $model->clave) {
                $model->setAttribute('clave', null);
                if ($model->update()) {
                    Yii::$app->session->setFlash('success', 'El usuario se ha comfirmado');
                    return $this->redirect(['/site/index']);
                } else {
                    Yii::$app->session->setFlash('error', 'la clave no es correcta.');
                }
            }
        }
    }

    protected function updatearEmpresa($exists, $empresa)
    {
        if (!$exists) {
            $empresa = new Empresas();
        }

        if ($empresa->load(Yii::$app->request->post()) && $empresa->save()) {
            Yii::$app->session->setFlash('success', 'Se ha modificado la empresa.');
        };

        return $empresa;
    }

    protected function sendMail($correo, $clave)
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['smtpUsername'])
            ->setTo($correo)
            ->setSubject('Avalon')
            ->setTextBody('Este correo ha sido generado para pruebas. Esta es su clave: ' . $clave)
            ->send();
    }

    protected function generarClave()
    {
        $matches = ['1', 'a', '2', 'b', '3', 'c', '4', 'd', '5', 'e', '6', 'f', '7', 'g', '8', 'h', '9', 'i'];
        shuffle($matches);
        $clave = [];
        for ($i = 0; $i <= 5; $i++) {
            $aux = array_rand($matches, 1);
            array_push($clave, $matches[$aux]);
        }
        return implode('', $clave);
    }
}
