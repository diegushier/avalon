<?php

namespace app\controllers;

use app\models\Empresas;
use app\models\ImageForm;
use app\models\Modificar;
use app\models\Paises;
use app\models\Usuariolibros;
use app\models\Usuarios;
use app\models\Usuarioshows;
use Yii;
use yii\bootstrap4\ActiveForm;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

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

    /**
     * Vista del usuario Logueado.
     */
    public function actionView($id = null)
    {
        if ($id === null) {
            $model = Yii::$app->user->identity;
        } else {
            $model = Usuarios::find()->where(['id' => $id])->one();
        }

        $pais = Paises::lista()[$model->pais_id];
        $render = [
            'model' => $model,
            'pais' => $pais,
        ];

        $empresa = $model->getEmpresas()->one();
        if ($empresa) {
            $emp_pais = Paises::lista()[$empresa->pais_id];
            $render += ['empresa' => $empresa];
            $render += ['emp_pais' => $emp_pais];
        }
        return $this->render('view', $render);
    }

    /**
     * Registro de un nuevo usuario.
     */
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

    /**
     * Eliminación o desvinculación de un usuario dependiendo de la situación.
     */
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

    public function actionSearch($entidad)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = Usuarios::find()->where(['id' => $entidad])->one();
        if (isset($model->clave)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Modificación de un usuario o su empresa.
     *
     * @return void
     */
    public function actionModify()
    {
        $params = Yii::$app->request->post();
        $model = Usuarios::find()->where(['id' => Yii::$app->user->id])->one();
        $imagen = new ImageForm();
        $paises = Paises::lista();

        $render = [
            'model' => $model,
            'paises' => ['' => ''] + $paises,
            'imagen' => $imagen,
        ];

        if ($model->load($params) && $model->save($params)) {
            if ($params['ImageForm']['imagen'] !== '') {
                $imagen->imagen = UploadedFile::getInstance($imagen, 'imagen');
                $imagen->upload($model->id, 'user');
            }

            Yii::$app->session->setFlash('success', 'Se ha modificado correctamente.');
            return $this->goHome();
        }

        return $this->render('modify', $render);
    }

    /**
     * Recuperación de la cuenta de un usuario mediante su correo.
     */
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

    public function actionLista()
    {
        $model = Yii::$app->user->identity->id;

        $show = Usuarioshows::find()
            ->where(['usuario_id' => $model])
            ->joinWith(['objetos o', 'objetos.integrante oi', 'objetos.productora op']);
        $libro = Usuariolibros::find()
            ->where(['usuario_id' => $model])
            ->joinWith(['libro l', 'libro.autor la', 'libro.editorial le']);

        $render = [
            'model'  => $model,
        ];

        if (Yii::$app->request->get()) {
            $dataName = Yii::$app->request->get('dataName', '');
            $age = Yii::$app->request->get('age', '');

            $show->where(['ilike', 'o.nombre', $dataName]);
            $libro->where(['ilike', 'l.nombre', $dataName]);

            $show->where(['oi.nombre' => $dataName]);
            $libro->where(['la.nombre' => $dataName]);

            $show->where(['ilike', 'op.nombre', $dataName]);
            $libro->where(['ilike', 'le.nombre', $dataName]);


            $render += [
                'dataName' => $dataName,
                'age' => $age,
            ];
        }

        if ($libro) {
            $render += ['libro' => $libro->all()];
        }

        if ($show) {
            $render += ['show' => $show->all()];
        }


        return $this->render('lista', $render);
    }

    protected function chech($data)
    {
        $result = [];
        foreach ($data as $k) {
            if (!empty($k)) {
                $result[] = $k;
            }
        }
        return $result;
    }

    /**
     * Comprobación de la existencia de un usuario en caso de requerir recuperar la cuenta.
     *
     * @param [string] $token
     */
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

    /**
     * Generación de una nueva clave de comfirmación para el usuario solicitante.
     *
     * @param [Usuario] $model
     * @param [POST] $params
     */
    public function actionUpclave($id, $clave)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        $model= Usuarios::find()->where(['id' => $id])->one();
        if ($clave === $model->clave) {
            $model->setAttribute('clave', null);
            if ($model->update()) {
                return  true;
            }
        }

        return false;
    }

    

    /**
     * Envio de un email al correo solicitante.
     *
     * @param [string] $correo
     * @param [string] $mensaje
     * @return void
     */
    protected function sendMail($correo, $mensaje)
    {
        Yii::$app->mailer->compose()
            ->setFrom(Yii::$app->params['smtpUsername'])
            ->setTo($correo)
            ->setSubject('no@replay')
            ->setHtmlBody($mensaje)
            ->send();
    }

    /**
     * Generación de una clave aleatória asignable a una cuenta.
     */
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
