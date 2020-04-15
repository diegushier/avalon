<?php

namespace app\controllers;

use app\models\Empresas;
use app\models\Modificar;
use app\models\Paises;
use app\models\Usuarios;
use Yii;
use yii\bootstrap4\ActiveForm;
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

    public function actionView()
    {
        $model = Yii::$app->user->identity;
        $pais = Paises::lista()[$model->pais_id];
        $empresa = $model->getEmpresas()->one();
        $emp_pais = Paises::lista()[$empresa->pais_id];
        return $this->render('view', [
            'model' => $model,
            'empresa' => $empresa,
            'pais' => $pais,
            'emp_pais' => $emp_pais,
        ]);
    }


    public function actionRegistrar()
    {
        $model = new Usuarios(['scenario' => Usuarios::SCENARIO_CREAR]);
        $clave = $this->generarClave();
        $model->setAttribute('clave', $clave);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $mensaje = 'Este correo ha sido generado para pruebas. Esta es su clave: ' . $clave;
            $this->sendMail($model->correo, $mensaje);
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

    public function actionDelete()
    {
        $params = Yii::$app->request->post();
        if ($params) {
            $model = Usuarios::findOne($params['id']);
            $empresa = $model->getEmpresas()->one();
            if ($empresa) {
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

    public function actionModify()
    {
        $params = Yii::$app->request->post();
        $usuario = Usuarios::find()->where(['id' => Yii::$app->user->id])->one();
        $empresa = $usuario->getEmpresas()->one();

        $model = new Modificar();
        $model->setAttributes([
            'nickname' => $usuario->nickname,
            'correo' => $usuario->correo,
            'pais_id' => $usuario->pais_id,
            'clave' => $usuario->clave,
            'passwd' => $usuario->passwd,
        ]);

        $paises = Paises::lista();
        $render = [
            'model' => $model,
            'paises' => ['' => ''] + $paises,
        ];

        if ($empresa) {
            $model->setAttributes([
                'nombre' => $empresa->nombre,
                'empresa_pais_id' => $empresa->pais_id,
                'entidad_id' => $empresa->entidad_id,
            ]);
            $render += ['empresa' => $empresa];
        }

        $this->updatearClave($usuario, $params);

        if ($model->create($params)) {
            Yii::$app->session->setFlash('success', 'Se ha modificado correctamente.');
            return $this->goHome();
        }


        if (Yii::$app->request->isAjax && $usuario->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $model->passwd = '';
        $model->passwd_repeat = '';

        return $this->render('modify', $render);
    }

    public function actionRecuperar()
    {
        $params = Yii::$app->request->post();
        if (isset($params['Usuarios'])) {
            $model = Usuarios::find()->where(['correo' => $params['Usuarios']['correo']])->one();
            if (isset($model)) {
                $model->scenario = Usuarios::SCENARIO_UPDATE;
                $model->comfirm = $this->generarClave();
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'El correo ha sido enviado.');
                    $mensaje = '<div style:"width:100%; background-color: #111;">
                                    <div style="width: 50%; margin: auto; height: 300px; border: 1px solid #FEB177; border-radius: 4px; text-align: center; background-color: #FEB177;">
                                    <h1 style="color:#fff;">Avalon</h1>
                                    <hr style="border: 1px solid #fff">
                                            <p style="color:#fff;">Si enviaste esta solicitud, pulsa en el siguiente botón, en caso contrario, deberías cambiar tu contraseña.</p>
                                            <br><br>
                                            <a href="' . getenv('URL') . '/index.php?r=usuarios/comprobar&token=' . $model->comfirm .
                        '" style="text-decoration: none; padding: 2%; border: 1px solid #ccc; border-radius: 4px; color: #111; background-color: #fff;">Recuperar Contraseña</a>
                                            <a href="' . getenv('URL') . '/index.php?r=site%2Flogin" style="margin-top: 2%; text-decoration: none; padding: 2%; border: 1px solid #ccc; color: #111; border-radius: 4px; background-color: #fff;">Login</a>
                                    </div>
                                </div>';
                    $this->sendMail($model->correo, $mensaje);
                }
            } else {
                $model = new Usuarios();
            }
        } else {
            $model = new Usuarios();
        }


        return $this->render('recuperar', [
            'model' => $model,
        ]);
    }

    public function actionComprobar($token)
    {
        $model = Usuarios::find()->where(['comfirm' => $token])->one();

        if (isset($model)) {
            $model->scenario = Usuarios::SCENARIO_UPDATE;
            $model->comfirm = null;
            $params = Yii::$app->request->post();

            if ($model->load($params) && $model->save()) {
                Yii::$app->session->setFlash('success', 'El usuario se ha modificado.');
                return $this->redirect(['/site/index']);
            }


            return $this->render('comprobar', [
                'model' => $model,
            ]);
        }

        return $this->render('/site/wrong');
    }

    protected function updatearClave($model, $params)
    {
        if (isset($params['Modificar']['clave'])) {
            if ($params['Modificar']['clave'] === $model->clave) {
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

    protected function sendMail($correo, $mensaje)
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['smtpUsername'])
            ->setTo($correo)
            ->setSubject('no@replay')
            ->setHtmlBody($mensaje)
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
