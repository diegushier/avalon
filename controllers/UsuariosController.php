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
        Yii::debug($params);

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
        if (isset($params)) {
            $model = Usuarios::find()->where(['correo' => $params['Usuarios']['correo']])->one();
            $model->scenario = Usuarios::SCENARIO_UPDATE;
            $model->clave = $this->generarClave();
            Yii::debug($model);
            if ($model->save(false)) {
                Yii::$app->session->setFlash('success', 'El correo ha sido enviado.');
                $mensaje = "<a href='http://localhost:8080/index.php?r=usuarios/comprobar&token=" . $model->clave . "'>Click Here to Reset Password</a>";
                $this->sendMail($model->correo, $mensaje);
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
        $model = Usuarios::find()->where(['clave' => $token])->one();
        $model->scenario = Usuarios::SCENARIO_UPDATE;
        $params = Yii::$app->request->post();

        if ($model->load($params) && $model->save()) {
            Yii::$app->session->setFlash('success', 'El usuario se ha modificado.');
            return $this->redirect(['/site/index']);
        }


        return $this->render('comprobar', [
            'model' => $model,
        ]);
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
