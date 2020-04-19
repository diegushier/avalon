<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;
use yii\web\UploadedFile;

/**
 * Clase creada para la manipulacíon de usuarios y empresas en una misma vista.
 */
class Modificar extends \yii\db\ActiveRecord
{
    public $passwd_repeat;
    public $user_id;
    public $passwd;
    public $nickname;
    public $correo;
    public $pais_id;
    public $clave;

    public $nombre;
    public $empresa_pais_id;
    public $entidad_id;

    public $imagen;

    const SCENARIO_UPDATE = 'update';

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nickname', 'nombre', 'correo'], 'required'],
            [['nickname', 'correo', 'auth_key'], 'string', 'max' => 255],
            [['passwd', 'passwd_repeat'], 'string', 'min' => 7],
            [['correo'], 'email'],
            [['pais_id', 'empresa_pais_id', 'entidad_id'], 'default', 'value' => null],
            [['pais_id', 'empresa_pais_id', 'entidad_id'], 'integer'],
            [['clave'], 'string', 'max' => 6],
            [
                ['passwd'],
                'trim',
                'on' => [self::SCENARIO_UPDATE],
            ],
            [
                ['passwd_repeat'],
                'compare',
                'compareAttribute' => 'passwd',
                'skipOnEmpty' => false,
            ],
            [['pais_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paises::className(), 'targetAttribute' => ['pais_id' => 'id']],
            [['empresa_pais_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paises::className(), 'targetAttribute' => ['pais_id' => 'id']],
            [['entidad_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['entidad_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nickname' => 'Nickname',
            'correo' => 'Correo',
            'clave' => 'Clave',
            'passwd' => 'Contraseña',
            'passwd_repeat' => 'Repetir contraseña',
            'pais_id' => 'Pais',
            'nombre' => 'Nombre',
            'empresa_pais_id' => 'Pais',
            'entidad_id' => 'Usuario',
        ];
    }

    /**
     * Manipulación de usuarios y empresas, así como de la subida de una imagen.
     *
     * @param [POST] $params
     * @return boolean
     */
    public function create($params)
    {
        if ($this->load($params)) {
            $usuarios = Usuarios::findOne(['id' => Yii::$app->user->id]);
            $empresas = $usuarios->getEmpresas()->one();
            $imagen = new ImageForm();
            $usuarios->scenario = Usuarios::SCENARIO_UPDATE;
            $usuarios->setAttributes([
                'nickname' => $this->nickname,
                'correo' => $this->correo,
                'clave' => $this->clave,
                'passwd' => $this->passwd,
                'passwd_repeat' => $this->passwd_repeat,
                'pais_id' => $this->pais_id,
            ]);

            if ($empresas) {
                $empresas->setAttributes([
                    'nombre' => $this->nombre,
                    'empresa_pais_id' => $this->pais_id,
                ]);
                if (!$empresas->save()) {
                    return false;
                }
            }

            $imagen->imagen = $this->imagen;
            if ($imagen->imagen !== null) {
                $imagen->upload($usuarios->id, 'user');
            }

            if ($usuarios->save()) {
                return true;
            }
        }
        return false;
    }
}
