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
 * @property Usuariorol $rol
 * @property Usuarioseguimiento[] $usuarioseguimientos
 * @property Valoraciones[] $valoraciones
 */
class Usuarios extends \yii\db\ActiveRecord implements IdentityInterface
{
    const SCENARIO_CREAR = 'crear';
    const SCENARIO_UPDATE = 'update';
    public $passwd_repeat;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nickname', 'username', 'correo'], 'required'],
            [['nickname', 'username', 'correo', 'auth_key'], 'string', 'max' => 255],
            [['passwd', 'passwd_repeat'], 'string', 'min' => 7],
            [['correo'], 'email'],
            [
                ['passwd'],
                'required',
                'on' => [self::SCENARIO_DEFAULT, self::SCENARIO_CREAR],
            ],
            [['pais_id', 'rol_id'], 'default', 'value' => null],
            [['pais_id', 'rol_id'], 'integer'],
            [
                ['passwd'],
                'trim',
                'on' => [self::SCENARIO_CREAR, self::SCENARIO_UPDATE],
            ],
            [['username'], 'unique'],
            [
                ['passwd_repeat'],
                'required',
                'on' => self::SCENARIO_CREAR
            ],
            [
                ['passwd_repeat'],
                'compare',
                'compareAttribute' => 'passwd',
                'skipOnEmpty' => false,
                'on' => [self::SCENARIO_CREAR, self::SCENARIO_UPDATE],
            ],
            [['pais_id'], 'exist', 'skipOnError' => true, 'targetClass' => Paises::className(), 'targetAttribute' => ['pais_id' => 'id']],
            [['rol_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuariorol::className(), 'targetAttribute' => ['rol_id' => 'id']],
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
            'username' => 'Username',
            'correo' => 'Correo',
            'passwd' => 'Contraseña',
            'passwd_repeat' => 'Repetir contraseña',
            'auth_key' => 'Auth Key',
            'pais_id' => 'Pais',
            'rol_id' => 'Rol ID',
        ];
    }

    /**
     * Gets query for [[Empresas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpresas()
    {
        return $this->hasOne(Empresas::className(), ['entidad_id' => 'id'])->inverseOf('entidad');
    }
    
    /**
     * Gets query for [[Pais]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPais()
    {
        return $this->hasOne(Paises::className(), ['id' => 'pais_id'])->inverseOf('usuarios');
    }

    /**
     * Gets query for [[Rol]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getRol()
    {
        return $this->hasOne(Usuariorol::className(), ['id' => 'rol_id'])->inverseOf('usuarios');
    }

    /**
     * Gets query for [[Usuarioseguimientos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioseguimientos()
    {
        return $this->hasMany(Usuarioseguimiento::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    /**
     * Gets query for [[Valoraciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getValoraciones()
    {
        return $this->hasMany(Valoraciones::className(), ['user_id' => 'id'])->inverseOf('user');
    }

    public static function findIdentity($id)
    {
        return static::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return $this->auth_key;
    }

    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    public static function findPorNombre($username)
    {
        return static::findOne(['username' => $username]);
    }

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->passwd);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }

        if ($insert) {
            if ($this->scenario === self::SCENARIO_CREAR) {
                $security = Yii::$app->security;
                $this->auth_key = $security->generateRandomString();
                $this->passwd = $security->generatePasswordHash($this->passwd);
            } else {
                if ($this->scenario === self::SCENARIO_UPDATE) {
                    if ($this->passwd === '') {
                        $this->passwd = $this->getOldAttribute('passwd');
                    } else {
                        $this->passwd = $security->generatePasswordHash($this->passwd);
                    }
                }
            }
        }
                
        return true;
    }
}