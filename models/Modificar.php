<?php

namespace app\models;

use Yii;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "usuarios".
 *
 * @property int $id
 * @property string $nickname
 * @property string $username
 * @property string $correo
 * @property string $passwd
 * @property string|null $auth_key
 * @property int|null $pais_id
 * @property int|null $rol_id
 *
 * @property Empresas $empresas
 * @property Paises $pais
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

    public function create($params)
    {
        if ($this->load($params)) {
            $usuarios = Usuarios::findOne(['id' => Yii::$app->user->id]);
            $empresas = Empresas::findOne(['entidad_id' => $usuarios->id]);

            $usuarios->scenario = Usuarios::SCENARIO_UPDATE;
            $usuarios->setAttributes([
                'nickname' => $this->nickname,
                'correo' => $this->correo,
                'clave' => $this->clave,
                'passwd' => $this->passwd,
                'passwd_repeat' => $this->passwd_repeat,
                'pais_id' => $this->pais_id,
            ]);

            $empresas->setAttributes([
                'nombre' => $this->nombre,
                'empresa_pais_id' => $this->pais_id,
            ]);

            if ($usuarios->save() && $empresas->save()) {
                return true;
            }
        }

        return false;
    }
}
